<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';
import { watch } from 'vue';
import { SharedData } from '@/types';

const page = usePage<SharedData>();
const toast = useToast();


// Watch flash.success dan tampilkan toast jika ada
watch(
  () => page.props.flash?.error,
  (message) => {
    if (message) {
      toast.error(message, {
        position: 'top-right',
        duration: 3000,
      });
    }
  },
  { immediate: true }
);
</script>

<template>
    <Head title="SiPeta-Wisata - Kabupaten Jayapura">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8">
        <!-- Header Navigation -->
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    :href="route('login')"
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm text-[#1b1b18] transition-all hover:border-[#20639a]"
                >
                    Masuk
                </Link>
                <Link
                    :href="route('register')"
                    class="inline-block rounded-sm border px-5 py-1.5 text-sm text-[#1b1b18] transition-all border-[#272727] hover:border-[#20639a]"
                >
                    Daftar
                </Link>
            </nav>
        </header>

        <!-- Main content -->
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow">
            <main class="flex w-full max-w-[335px] flex-col overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <!-- Logo / Gambar di kiri -->
                <div class="relative flex w-full items-center justify-center bg-[#d3ecff] p-6 lg:w-[300px]">
                    <img src="image/logo_transparant.png" alt="Logo SiPeta-Wisata" class="max-h-48 w-auto" />
                </div>

                <!-- Informasi / Teks di kanan -->
                <div
                    class="flex-1 bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:p-20"
                >
                    <h1 class="mb-1 text-xl font-semibold">Sistem Informasi Pemetaan Potensi Wisata</h1>
                    <p class="mb-2 text-[#4a4a48] dark:text-[#A1A09A]">
                        Berbasis Web dengan Metode <strong>TOPSIS</strong> pada Dinas Pariwisata Kabupaten Jayapura.
                    </p>

                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a
                                :href="route('login')"
                                class="inline-block rounded-sm border border-[#20639a] bg-[#20639a] px-5 py-1.5 text-white transition-all hover:bg-[#20519a]"
                            >
                                Mulai Eksplorasi
                            </a>
                        </li>
                    </ul>
                </div>
            </main>
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
