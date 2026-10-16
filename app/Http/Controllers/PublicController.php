<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Budget;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\LostItem;
use App\Models\Post;
use App\Models\Potential;
use App\Models\VillageLocation;
use App\Models\VillageProfile;
use App\Models\VillageStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $base = Post::published()->with(['category', 'author']);

        // Headline: berita "featured" terbaru, atau berita terbaru jika belum ada
        $featured = (clone $base)->where('is_featured', true)->latest('published_at')->first()
            ?? (clone $base)->latest('published_at')->first();

        $exclude = $featured ? [$featured->id] : [];

        $sideHeadlines = (clone $base)
            ->whereNotIn('id', $exclude)
            ->latest('published_at')
            ->take(3)
            ->get();

        $exclude = array_merge($exclude, $sideHeadlines->pluck('id')->all());

        $latestPosts = (clone $base)
            ->whereNotIn('id', $exclude)
            ->latest('published_at')
            ->take(6)
            ->get();

        $announcements = Announcement::published()
            ->orderByDesc('is_important')
            ->latest('published_at')
            ->take(5)
            ->get();

        $events = Event::published()
            ->upcoming()
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(4)
            ->get();

        $galleryImages = GalleryImage::with('gallery')
            ->whereHas('gallery', fn ($q) => $q->published())
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $stats = Cache::remember('home.stats', 3600, fn () => VillageStatistic::orderBy('sort_order')->get());

        $profile = VillageProfile::current();
        $profileExcerpt = Str::limit(
            trim(strip_tags(Str::markdown((string) $profile->about, ['html_input' => 'strip']))),
            280
        );

        // Potensi desa: 4 terbaru yang tampil (diatur dari admin)
        $potentials = Potential::published()->orderBy('sort_order')->latest('id')->take(4)->get();

        // Barang hilang & ditemukan yang masih aktif
        $lostItems = LostItem::published()->active()->latest('event_date')->latest('id')->take(3)->get();

        // Ringkasan APBDes tahun terbaru yang dipublikasikan
        $budget = Budget::published()->orderByDesc('year')->first();

        // Foto hero: foto profil desa jika sudah diunggah. Jika belum, view memakai ilustrasi bawaan.
        $heroImage = filled($profile->hero_image) ? $profile->hero_image_url : null;
        $introPhoto = $heroImage ?? $galleryImages->first()?->image_url;
        $secondPhoto = $galleryImages->skip(1)->first()?->image_url;

        // Ringkasan peta: hanya lokasi yang sudah dipublikasikan admin
        $mapLocations = VillageLocation::query()->where('is_published', true)->get(['category']);
        $mapCount = $mapLocations->count();
        $mapCategories = $mapLocations->pluck('category')->filter()->unique()->values();

        return view('home', compact(
            'heroImage',
            'introPhoto',
            'secondPhoto',
            'mapCount',
            'mapCategories',
            'featured',
            'sideHeadlines',
            'latestPosts',
            'announcements',
            'events',
            'galleryImages',
            'stats',
            'profileExcerpt',
            'potentials',
            'lostItems',
            'budget'
        ));
    }

    /** GET /search?q=... - cari di judul, isi, dan excerpt berita. */
    public function search(Request $request): View
    {
        $q = Str::limit(trim((string) $request->query('q', '')), 100, '');
        $posts = null;

        if (mb_strlen($q) >= 2) {
            // Escape karakter wildcard LIKE agar % dan _ dicari apa adanya
            $like = '%'.addcslashes($q, '%_\\').'%';

            $posts = Post::published()
                ->with(['category', 'author'])
                ->where(function ($w) use ($like) {
                    $w->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->latest('published_at')
                ->paginate(8)
                ->onEachSide(1)
                ->withQueryString();
        }

        return view('search', compact('q', 'posts'));
    }

    /** GET /informasi - informasi pelayanan untuk masyarakat. */
    public function information(): View
    {
        // Persyaratan: TEKS CONTOH. Sesuaikan dengan ketentuan resmi Desa Klepu.
        $services = [
            ['Surat Keterangan Domisili', ['Fotokopi KTP dan Kartu Keluarga', 'Surat pengantar RT/RW', 'Pas foto sesuai kebutuhan']],
            ['Surat Pengantar KTP / KK', ['Surat pengantar RT/RW', 'Fotokopi dokumen pendukung (akta, KK lama, dsb.)', 'Mengisi formulir di kantor desa']],
            ['Surat Keterangan Usaha', ['Fotokopi KTP dan Kartu Keluarga', 'Surat pengantar RT/RW', 'Keterangan singkat jenis dan lokasi usaha']],
            ['Surat Keterangan Tidak Mampu', ['Fotokopi KTP dan Kartu Keluarga', 'Surat pengantar RT/RW', 'Keperluan penggunaan surat']],
        ];

        $publicInfo = [
            'Struktur pemerintahan desa dapat dilihat pada halaman Pemerintahan.',
            'Pengumuman resmi desa diterbitkan pada halaman Pengumuman.',
            'Jadwal kegiatan desa tersedia pada halaman Agenda.',
            'Permintaan informasi publik lainnya dapat disampaikan langsung ke kantor desa.',
        ];

        $announcements = Announcement::published()
            ->orderByDesc('is_important')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('informasi.index', compact('services', 'publicInfo', 'announcements'));
    }
}
