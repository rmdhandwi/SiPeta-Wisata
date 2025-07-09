<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase
        title="Masuk ke SiPeta-Wisata"
        description="Masukkan username dan kata sandi Anda untuk memulai menjelajahi wisata."
    >
        <Head title="Masuk" />

        <!-- Status Message -->
        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-blue-600"
        >
            {{ status }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="flex flex-col gap-6 text-black">
            <div class="grid gap-6">
                <!-- Username -->
                <div class="grid gap-2">
                    <Label for="username" class="text-black">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        autofocus
                        :tabindex="1"
                        :aria-invalid="!!form.errors.username"
                        autocomplete="username"
                        v-model="form.username"
                        placeholder="Masukkan username"
                        class="text-black"
                    />
                    <InputError :message="form.errors.username" />
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-black">Kata Sandi</Label>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        :aria-invalid="!!form.errors.password"
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="••••••••"
                        class="text-black"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Submit Button -->
                <Button
                    type="submit"
                    class="mt-4 w-full bg-[#1e7fce] hover:bg-[#20639a] cursor-pointer text-white transition-all"
                    :tabindex="4"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Masuk
                </Button>
            </div>

            <!-- Sign up link (optional) -->
            
            <div class="text-center text-sm text-gray-600">
                Belum punya akun?
                <TextLink :href="route('register')" :tabindex="5" class="underline underline-offset-4 hover:underline hover:text-[#20639a] dark:hover:text-[#20639a] dark:text-gray-400 cursor-pointer transition-all">Daftar sekarang</TextLink>
            </div>
           
        </form>
    </AuthBase>
</template>


