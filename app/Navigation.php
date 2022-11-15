<?php

namespace App;

use App\Models\User;

class Navigation
{
    public function __invoke(User $user)
    {
        $user->load(['roles', 'permissions']);

        return [
            ...$this->main($user),
            // ...$this->sales($user),
            // ...$this->inventory($user),
            ...$this->blog($user),
            ...$this->info($user),
            // ...$this->navigation($user),
            ...$this->survey($user),
            ...$this->services($user),
            ...$this->gratifikasi($user),
            ...$this->settings($user),
        ];
    }

    public function main(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Dashboard',
                'href' => route('dashboard'),
                'icon' => 'dashboard',
                'key' => 'dashboard',
            ],
            // [
            //     "type" => "link",
            //     "name" => "Cashier",
            //     "href" => route("point-of-sale.cashier"),
            //     "icon" => "cashier",
            //     "key" => "point-of-sale",
            // ]
        ];

        foreach ($data as $item) {
            if ($item['type'] === 'separator') {
                $result[] = $item;
            } elseif ($user->can($item['key'])) {
                $result[] = $item;
            }
        }

        $result = [
            [
                'type' => 'link',
                'name' => 'Visit Website',
                'href' => route('home'),
                'target' => '_blank',
                'icon' => 'visit-website',
            ], [
                'type' => 'separator',
            ],
            ...$result,
        ];

        return $result;
    }

    public function blog(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Tulisan',
                'href' => route('kedeka::admin.blog.post.index'),
                'icon' => 'posts',
                'key' => 'blog.post',
            ],
            [
                'type' => 'link',
                'name' => 'Kategori',
                'href' => route('kedeka::admin.blog.category.index'),
                'icon' => 'categories',
                'key' => 'blog.category',
            ],
            [
                'type' => 'link',
                'name' => 'Penulis',
                'href' => route('kedeka::admin.blog.author.index'),
                'icon' => 'authors',
                'key' => 'blog.author',
            ],

        
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Blog',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    
    
    public function info(User $user)
    {
        $result = [];
        $data = [

            [
                'type' => 'link',
                'name' => 'Agenda',
                'href' => route('admin.agenda.index'),
                'icon' => 'agenda-icon',
                'key' => 'agenda',
            ],

            [
                'type' => 'link',
                'name' => 'Berita',
                'href' => route('admin.news.index'),
                'icon' => 'berita-icon',
                'key' => 'news',
            ],

            [
                'type' => 'link',
                'name' => 'Aplikasi',
                'href' => route('admin.aplikasi.index'),
                'icon' => 'apl-icon',
                'key' => 'aplikasi',
            ],

            [
                'type' => 'link',
                'name' => 'Dokumen',
                'href' => route('admin.document.index'),
                'icon' => 'document-icon',
                'key' => '#',
            ],
            

        
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Info',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    public function navigation(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Grup',
                'href' => route('kedeka::admin.navigation.group.index'),
                'icon' => 'navigation-group',
                'key' => 'navigation.group',
            ],
            [
                'type' => 'link',
                'name' => 'Navigasi',
                'href' => route('kedeka::admin.navigation.navigation.index'),
                'icon' => 'navigation',
                'key' => 'navigation',
            ],
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Navigation',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    public function survey(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Pertanyaan',
                'href' => route('kedeka::admin.survey.question.index'),
                'icon' => 'survey-question',
                'key' => 'survey.question',
            ],
            [
                'type' => 'link',
                'name' => 'Jawaban',
                'href' => route('kedeka::admin.survey.answer.index'),
                'icon' => 'survey-answer',
                'key' => 'survey.answer',
            ],
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Survey',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    public function settings(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Navigasi',
                'href' => route('kedeka::admin.navigation.navigation.index'),
                'icon' => 'navigation',
                'key' => 'navigation',
            ],
            [
                'type' => 'link',
                'name' => 'Slider',
                'href' => route('kedeka::admin.slider.index'),
                'icon' => 'slider',
                'key' => 'slider',
            ],
            [
                'type' => 'link',
                'name' => 'Sekilas Info',
                'href' => route('kedeka::admin.news-flash.index'),
                'icon' => 'news-flash',
                'key' => 'news-flash',
            ],
            [
                'type' => 'link',
                'name' => 'Halaman',
                'href' => route('kedeka::admin.page.index'),
                'icon' => 'page',
                'key' => 'page',
            ],
            [
                'type' => 'link',
                'name' => 'Pesan',
                'href' => route('kedeka::admin.contact.index'),
                'icon' => 'contact',
                'key' => 'contact',
            ],
            [
                'type' => 'link',
                'name' => 'Kategori Izin',
                'href' => route('admin.permit-categories.index'),
                'icon' => 'permit-categories',
                'key' => 'permit-categories',
            ],
            [
                'type' => 'link',
                'name' => 'Kategori WBS',
                'href' => route('admin.wbs.kategori.index'),
                'icon' => 'wbs-categories',
                'key' => 'wbs-categories',
            ],
            // [
            //     "type" => "link",
            //     "name" => "Users",
            //     "href" => route("dash::user.index"),
            //     "icon" => "authors",
            //     "key" => "user",
            // ],
            [
                'type' => 'link',
                'name' => 'Pengaturan',
                'href' => route('kedeka::admin.setting.show'),
                'icon' => 'setting',
                'key' => 'setting',
            ],
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'separator',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    public function services(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Cagar Budaya',
                'href' => route('admin.services.cagar-budaya.index'),
                'icon' => 'services-cagar-budaya',
                'key' => 'services.cagar-budaya',
            ],
            [
                'type' => 'link',
                'name' => 'Laporan WBS',
                'href' => route('admin.wbs.laporan.index'),
                'icon' => 'wbs-report',
                'key' => 'wbs-report',
            ],
            [
                'type' => 'link',
                'name' => 'Perizinan',
                'href' => route('admin.services.permit.index'),
                'icon' => 'services-permit',
                'key' => 'services.permit',
            ],
            [
                'type' => 'link',
                // 'name' => 'Pelaporan Temuan',
                'name' => 'Temuan',
                'href' => route('admin.services.report.index'),
                'icon' => 'services-report',
                'key' => 'services.report',
            ],
            [
                'type' => 'link',
                // 'name' => 'Permohonan Data CB',
                'name' => 'Data CB',
                'href' => route('admin.services.data-request.index'),
                'icon' => 'services-data-request',
                'key' => 'services.data-request',
            ],
            [
                'type' => 'link',
                // 'name' => 'Pelaporan Kasus CB',
                'name' => 'Kasus CB',
                'href' => route('admin.services.case.index'),
                'icon' => 'services-case',
                'key' => 'services.case',
            ],
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Layanan',
                ],
                ...$result,
            ];
        }

        return $result;
    }

    public function gratifikasi(User $user)
    {
        $result = [];
        $data = [
            [
                'type' => 'link',
                'name' => 'Jenis',
                'href' => route('admin.gratifikasi.jenis.index'),
                'icon' => 'gratifikasi-jenis',
                'key' => 'gratifikasi.jenis',
            ],
            [
                'type' => 'link',
                'name' => 'Peristiwa',
                'href' => route('admin.gratifikasi.peristiwa.index'),
                'icon' => 'gratifikasi-peristiwa',
                'key' => 'gratifikasi.peristiwa',
            ],
            [
                'type' => 'link',
                'name' => 'Laporan',
                'href' => route('admin.gratifikasi.laporan.index'),
                'icon' => 'gratifikasi-laporan',
                'key' => 'gratifikasi.laporan',
            ],
        ];

        foreach ($data as $item) {
            if ($user->can('dashboard.'.$item['key'].'.index') || true) {
                $result[] = $item;
            }
        }

        if (! empty($result)) {
            $result = [
                [
                    'type' => 'label',
                    'label' => 'Gratifikasi',
                ],
                ...$result,
            ];
        }

        return $result;
    }
}
