<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img src="../../../public/image/logo.jpg" width="200" height="200" alt="Icon Logo" srcset="">
</template>
