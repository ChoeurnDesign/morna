<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { Motion } from '@motionone/vue';

const props = defineProps({
    faqs: {
        type: Array,
        default: () => [],
    },
});

const openId = ref(null);

const toggle = (id) => {
    openId.value = openId.value === id ? null : id;
};
</script>

<template>
    <GuestLayout title="FAQ">
        <div class="px-10 sm:px-14 lg:px-20">
            <!-- Header / intro -->
            <Motion
                class="mb-8 sm:mb-10 space-y-3 sm:space-y-4"
                :initial="{ opacity: 0, y: 20 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.55, easing: 'ease-out' }"
            >
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Frequently asked questions
                </h1>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl leading-relaxed">
                    Quick answers about Morna Mulberry, our mulberry drink, and how to
                    store or enjoy it.
                </p>
            </Motion>

            <section>
                <!-- FAQ list -->
                <Motion
                    v-if="faqs.length"
                    class="divide-y divide-gray-200 rounded-2xl border bg-white shadow-sm overflow-hidden"
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.5, delay: 0.1, easing: 'ease-out' }"
                >
                    <Motion
                        v-for="(faq, index) in faqs"
                        :key="faq.id"
                        class="px-4 sm:px-6"
                        :initial="{ opacity: 0, y: 10 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{ duration: 0.35, delay: 0.05 * index, easing: 'ease-out' }"
                    >
                        <button
                            type="button"
                            class="w-full py-3.5 sm:py-4 flex items-center justify-between gap-3 text-left"
                            @click="toggle(faq.id)"
                        >
                            <div class="flex-1">
                                <p class="text-sm sm:text-base font-medium text-gray-900">
                                    {{ faq.question }}
                                </p>
                                <p
                                    v-if="faq.category"
                                    class="text-[11px] sm:text-xs text-purple-600 mt-1"
                                >
                                    {{ faq.category }}
                                </p>
                            </div>
                            <ChevronDownIcon
                                class="w-5 h-5 text-gray-500 transition-transform duration-200 flex-shrink-0"
                                :class="{ 'rotate-180': openId === faq.id }"
                            />
                        </button>

                        <Transition name="accordion">
                            <div
                                v-show="openId === faq.id"
                                class="pb-3.5 sm:pb-4 text-sm text-gray-700"
                            >
                                <p
                                    class="border-t border-gray-100 pt-3 whitespace-pre-line leading-relaxed"
                                >
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </Transition>
                    </Motion>
                </Motion>

                <!-- Empty state -->
                <Motion
                    v-else
                    class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-5 sm:p-6 text-center text-xs sm:text-sm text-gray-500"
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.4, easing: 'ease-out' }"
                >
                    No FAQs yet. Add them in the admin panel to help visitors learn more
                    about your drink.
                </Motion>
            </section>
        </div>
    </GuestLayout>
</template>

<style scoped>
.accordion-enter-active,
.accordion-leave-active {
    transition: all 0.2s ease-out;
}
.accordion-enter-from,
.accordion-leave-to {
    opacity: 0;
    max-height: 0;
}
.accordion-enter-to,
.accordion-leave-from {
    opacity: 1;
    max-height: 200px;
}
</style>