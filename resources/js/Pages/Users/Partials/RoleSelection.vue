<script setup>
import {RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption} from "@headlessui/vue";
import {CheckBadgeIcon} from "@heroicons/vue/16/solid/index.js";

const model = defineModel({
    type: String,
});
</script>

<template>
    <div class="w-full">
        <div class="w-full">
            <RadioGroup v-model="model">
                <div class="space-y-2">
                    <RadioGroupOption v-slot="{ checked }"
                                      as="template"
                                      value="admin">
                        <div :class="checked ? 'bg-primary-50 border-primary-200 ' : 'bg-white '"
                             class="relative flex cursor-pointer border rounded-md px-3 py-2 focus:outline-none">
                            <div class="flex w-full items-center justify-between">
                                <div class="flex flex-col text-sm">
                                    <RadioGroupLabel
                                        as="p"
                                        class="font-medium"
                                    >
                                        Admin
                                    </RadioGroupLabel>
                                    <RadioGroupDescription
                                        as="span"
                                        class="inline text-wrap text-neutral-500"
                                    >
                                        Provides access to the entire system.
                                    </RadioGroupDescription>
                                </div>
                                <CheckBadgeIcon :class="{ 'invisible': !checked }"
                                                class="size-6 text-primary-600" />
                            </div>
                        </div>
                    </RadioGroupOption>
                    <RadioGroupOption v-slot="{ checked }"
                                      as="template"
                                      value="regular">
                        <div :class="checked ? 'bg-primary-50 border-primary-200 ' : 'bg-white '"
                             class="relative flex cursor-pointer border rounded-md px-3 py-2 focus:outline-none">
                            <div class="flex w-full items-center justify-between">
                                <div class="flex flex-col text-sm">
                                    <RadioGroupLabel
                                        as="p"
                                        class="font-medium"
                                    >
                                        Regular
                                    </RadioGroupLabel>
                                    <RadioGroupDescription
                                        as="span"
                                        class="inline text-wrap text-neutral-500"
                                    >
                                        Provides access to the entire system excluding user management, product deletion, and quotation approval.
                                    </RadioGroupDescription>
                                </div>
                                <CheckBadgeIcon :class="{ 'invisible': !checked }"
                                                class="size-6 text-primary-600" />
                            </div>
                        </div>
                    </RadioGroupOption>
                    <RadioGroupOption v-slot="{ checked }"
                                      as="template"
                                      value="affiliate">
                        <div :class="checked ? 'bg-primary-50 border-primary-200 ' : 'bg-white '"
                             class="relative flex cursor-pointer border rounded-md px-3 py-2 focus:outline-none">
                            <div class="flex w-full items-center justify-between">
                                <div class="flex flex-col text-sm">
                                    <RadioGroupLabel
                                        as="p"
                                        class="font-medium"
                                    >
                                        Affiliate
                                    </RadioGroupLabel>
                                    <RadioGroupDescription
                                        as="span"
                                        class="inline text-wrap text-neutral-500"
                                    >
                                        Given to customers. Provides access to the products list and their own quotaton list.
                                    </RadioGroupDescription>
                                </div>
                                <CheckBadgeIcon :class="{ 'invisible': !checked }"
                                                class="size-6 text-primary-600" />
                            </div>
                        </div>
                    </RadioGroupOption>
                </div>
            </RadioGroup>
        </div>
    </div>
</template>
