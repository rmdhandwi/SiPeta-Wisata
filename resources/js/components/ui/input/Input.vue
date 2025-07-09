<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <input
    v-model="modelValue"
    data-slot="input"
    :class="cn(
      // Base input styles
      'flex h-9 w-full min-w-0 rounded-md px-3 py-1 text-base shadow-xs outline-none transition-[color,box-shadow]',
      
      // Background and text color
      'bg-white text-black placeholder:text-gray-400 selection:bg-[#20639a] selection:text-white',

      // Border and focus styles
      'border border-gray-300 focus:border[#20639a] focus:ring-2 focus:ring-[#20639a]',

      // File input (if applicable)
      'file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium',

      // Disabled styles
      'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',

      // Responsive
      'md:text-sm',

      // Validation states (optional)
      'aria-invalid:border-red-500 aria-invalid:ring-red-100',

      // Allow additional classes via props
      props.class
    )"
  >
</template>

