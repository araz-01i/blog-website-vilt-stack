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
      'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white',
      props.class,
    )"
  />
</template>

<style>
/* Custom focus styles */
input[data-slot="input"]:focus {
  outline: none !important;
  outline-offset: 0cm !important;
  outline-color: transparent !important;
  box-shadow: none !important;

  border-color: #3b82f6 !important; /* blue-500 */
}

/* Dark mode focus styles */
.dark input[data-slot="input"]:focus {
  outline: none !important;
  outline-offset: 0cm !important;
  outline-color: transparent !important;
  box-shadow: none !important;

  border-color: #3b82f6 !important;
}
</style>
