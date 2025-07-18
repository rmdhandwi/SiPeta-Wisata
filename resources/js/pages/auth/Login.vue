<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { SharedData } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { watch } from 'vue';
import { useToast } from 'vue-toast-notification';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const page = usePage<SharedData>();
const toast = useToast();

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
    { immediate: true },
);

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            toast.success(message, {
                position: 'top-right',
                duration: 3000,
            });
        }
    },
    { immediate: true },
);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Masuk ke SiPeta-Wisata" description="Masukkan username dan kata sandi Anda untuk memulai menjelajahi wisata.">
        <Head title="Masuk" />

        <!-- Status Message -->
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-blue-600">
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
                    class="mt-4 w-full cursor-pointer bg-[#1e7fce] text-white transition-all hover:bg-[#20639a]"
                    :tabindex="4"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Masuk
                </Button>
            </div>

            <!-- Sign up link (optional) -->

            <!-- <div class="text-center text-sm text-gray-600">
                Belum punya akun?
                <TextLink
                    :href="route('register')"
                    :tabindex="5"
                    class="cursor-pointer underline underline-offset-4 transition-all hover:text-[#20639a] hover:underline dark:text-gray-400 dark:hover:text-[#20639a]"
                    >Daftar sekarang</TextLink
                >
            </div> -->
        </form>
    </AuthBase>
</template>
