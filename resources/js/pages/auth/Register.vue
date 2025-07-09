<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    role: 3,
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Daftar Akun" description="Isi detail Anda untuk mendaftar akun baru">
        <Head title="Daftar" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label class="text-black" for="name">Nama Lengkap</Label>
                    <Input
                        id="name"
                        type="text"
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Nama Lengkap"
                        class="bg-white text-black border"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label class="text-black" for="username">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        :tabindex="2"
                        autocomplete="username"
                        v-model="form.username"
                        placeholder="Username"
                        class="bg-white text-black border"
                    />
                    <InputError :message="form.errors.username" />
                </div>

                <div class="grid gap-2">
                    <Label class="text-black" for="email">Alamat Email</Label>
                    <Input
                        id="email"
                        type="email"
                        :tabindex="3"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@contoh.com"
                        class="bg-white text-black border"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label class="text-black" for="password">Kata Sandi</Label>
                    <Input
                        id="password"
                        type="password"
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Kata Sandi"
                        class="bg-white text-black border"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label class="text-black" for="password_confirmation">Konfirmasi Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        :tabindex="5"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Konfirmasi Password"
                        class="bg-white text-black border"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full bg-[#1e7fce] hover:bg-[#20639a] cursor-pointer text-white transition-all"
                    :tabindex="6"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Daftar Sekarang
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground mt-4">
                Sudah punya akun?
                <TextLink
                    :href="route('login')"
                    class="underline underline-offset-4 hover:underline hover:text-[#20639a] dark:hover:text-[#20639a] dark:text-gray-400 cursor-pointer transition-all"
                    :tabindex="7"
                >
                    Masuk
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>

