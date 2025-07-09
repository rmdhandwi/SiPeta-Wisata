import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    match?: string[];
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    role: number;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface JenisWisata{
    id_jenis_wisata?: number;
    nama_jenis_wisata: string;
    keterangan: string;
}

export interface LokasiWisataItem {
    [key: string]: any;
}

export interface Kriteria{
    id_kriteria?: number,
    nama_kriteria: string,
    bobot_kriteria: string,
    tipe_kriteria: string
}

export interface Subkriteria{
    id_subkriteria?: number,
    kriteria_id: string,
    nama_subkriteria: string,
    bobot_subkriteria: string,
}

export interface Alternatif{
    id_alternatif?: number,
    lokasi_wisata_id: string,
    subkriteria_id: string,
    nilai: string,
}

export type BreadcrumbItemType = BreadcrumbItem;
