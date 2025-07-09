<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookMarked, Calculator, ClipboardList, FileBarChart, FileUser, LayoutGrid, Map, MapPinned } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage<SharedData>();

const role = computed(() => page.props.auth?.user.role); // Ambil role dari backend

const mainNavItems = computed<NavItem[]>(() => {
    switch (role.value) {
        case 1: // Admin
            return [
                {
                    title: 'Dashboard',
                    href: '/dashboard',
                    icon: LayoutGrid,
                },
                {
                    title: 'Jenis Wisata',
                    href: '/admin/jeniswisata',
                    icon: BookMarked,
                    match: ['/admin/jeniswisata', '/admin/jeniswisata/*'],
                },
                {
                    title: 'Lokasi Wisata',
                    href: '/admin/lokasiwisata',
                    icon: MapPinned,
                    match: ['/admin/lokasiwisata', '/admin/lokasiwisata/*'],
                },
                {
                    title: 'Kriteria',
                    href: '/admin/kriteria',
                    icon: ClipboardList,
                    match: ['/admin/kriteria', '/admin/kriteria/*', '/admin/subkriteria', '/admin/subkriteria/*'],
                },
                {
                    title: 'Alternatif',
                    href: '/admin/alternatif',
                    icon: FileUser,
                    match: ['/admin/alternatif', '/admin/alternatif/*'],
                },
                {
                    title: 'Topsis dan Hasil',
                    href: '/admin/topsis',
                    icon: Calculator,
                    match: ['/admin/topsis', '/admin/potensi'],
                },
                {
                    title: 'Pemetaan',
                    href: '/admin/pemetaan',
                    icon: Map,
                },
            ];
        case 2: // Kepala Dinas
            return [
                {
                    title: 'Dashboard',
                    href: '/dashboard',
                    icon: LayoutGrid,
                },
                {
                    title: 'Laporan Wisata',
                    href: '/laporan',
                    icon: FileBarChart,
                },
            ];
        case 3: // Investor
            return [
                {
                    title: 'Dashboard',
                    href: '/dashboard',
                    icon: LayoutGrid,
                },
                {
                    title: 'Investasi Saya',
                    href: '/investasi',
                    icon: BookMarked,
                },
            ];
        default:
            return [{ title: 'Dashboard', href: '/dashboard', icon: LayoutGrid }];
    }
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
