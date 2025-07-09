<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall title="Hapus Akun" description="Hapus akun Anda dan seluruh datanya" />

        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4">
            <div class="relative space-y-0.5 text-red-600">
                <p class="font-medium">Peringatan</p>
                <p class="text-sm">Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>
            </div>

            <Dialog>
                <DialogTrigger as-child>
                    <Button class="cursor-pointer" variant="destructive">Hapus Akun</Button>
                </DialogTrigger>

                <DialogContent>
                    <form class="space-y-6" @submit="deleteUser">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Apakah Anda yakin ingin menghapus akun?</DialogTitle>
                            <DialogDescription>
                                Setelah akun Anda dihapus, semua data dan sumber daya akan terhapus secara permanen. Silakan masukkan kata sandi
                                Anda untuk mengonfirmasi penghapusan.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">Kata Sandi</Label>
                            <Input id="password" type="password" name="password" ref="passwordInput" v-model="form.password" placeholder="Kata sandi" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" class="cursor-pointer" @click="closeModal">Batal</Button>
                            </DialogClose>

                            <Button variant="destructive" :disabled="form.processing">
                                <button class="cursor-pointer" type="submit">Hapus Akun</button>
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>

