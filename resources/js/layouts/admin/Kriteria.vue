<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Data Kriteria',
        href: '/admin/kriteria',
    },
    {
        title: 'Form Kriteria',
        href: '/admin/kriteria/create',
        match: ['/admin/kriteria/create', '/admin/kriteria/*'],
    },
    {
        title: 'Data Sub Kriteria',
        href: '/admin/subkriteria',
    },
    {
        title: 'Form Sub Kriteria',
        href: '/admin/subkriteria/create',
        match: ['/admin/subkriteria/create', '/admin/subkriteria/*'],
    },
];

function isActive(item: NavItem): boolean {
    if (item.href === currentPath) {
        return true;
    }

    if (item.match && Array.isArray(item.match)) {
        return item.match.some((pattern) => {
            // Ubah wildcard jadi regex sederhana
            const regex = new RegExp('^' + pattern.replace(/\*/g, '.*') + '$');
            return regex.test(currentPath);
        });
    }

    return false;
}

const page = usePage<SharedData>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="px-4 py-6">
        <Heading title="Kriteria & Sub Kriteria" description="Kelola data kriteria dan sub kriteria" />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-y-0 lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-y-1 space-x-0">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': isActive(item) }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1 md:max-w-screen">
                <section class="max-w-screen space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
