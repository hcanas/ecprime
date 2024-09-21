<script setup>
import {Head} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
</script>

<template>
    <BaseLayout>
        <section class="max-w-7xl mx-auto px-3 xl:px-0 py-6">
            <Head title="About Us" />

            <div class="flex flex-col xl:flex-row items-center justify-center gap-x-12">
                <div class="flex flex-col gap-y-6">
                    <div class="flex flex-col justify-center">
                        <h1 class="uppercase font-bold dark:text-neutral-100 leading-3">About</h1>
                        <h1 class="uppercase text-primary-500 dark:text-primary-400 font-bold leading-relaxed">EC PRIME</h1>
                        <h1 class="uppercase font-medium dark:text-neutral-100 leading-3">Corporation</h1>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="font-medium italic">Mission</h3>
                        <p class="text-justify">To excel in the realm of general trading within the Philippines, engaging in the procurement, distribution, and marketing of a diverse array of goods, commodities, and merchandise in compliance with local regulations. We commit ourselves to fostering growth, innovation, and sustainability in every transaction, ensuring value for our customers and contributing positively to the economic landscape of the Philippines.</p>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="font-medium italic">Vision</h3>
                        <p class="text-justify">To establish ourselves as a leader in the general trading sector, renowned for our integrity, quality, and comprehensive service offerings. We aspire to be a cornerstone of the Philippine economy, enhancing the flow of goods both domestically and internationally, and setting new standards for excellence and ethical business practices in the trading community.</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <img class="hidden xl:inline h-[600px]"
                         src="/images/about_us2.png" />
                </div>
            </div>

            <div class="flex flex-col items-center gap-y-12 mt-24">
                <div class="flex flex-col gap-y-3">
                    <h2 class="font-bold">DECON MEDICAL</h2>

                    <div class="flex flex-col gap-y-3 xl:flex-row xl:items-start xl:gap-x-6">
                        <div class="xl:order-2">
                            <img class="w-[60rem]"
                                 src="/images/hemodialysis.jpg" />
                        </div>
                        <div class="xl:w-[40rem] flex flex-col gap-y-3 xl:order-1">
                            <div class="flex flex-col">
                                <h3 class="font-medium italic">Mission</h3>
                                <p class="text-justify">To elevate healthcare standards in the Philippines by specializing in hemodialysis and diagnostics, ensuring every Filipino receives the highest quality of care. Through public-private partnerships with the government and the support of local government units, our centers are committed to making advanced healthcare services accessible and affordable, fostering a healthier nation. We are dedicated to reaching out and providing compassionate care, aiming to make a significant difference in the lives of the people we serve.</p>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-medium italic">Vision</h3>
                                <p class="text-justify">To be at the forefront of healthcare innovation in the Philippines, where our pioneering services in hemodialysis and diagnostics redefine patient care and wellness. We envision a future where our collaborative efforts with the government and the community ensure that every Filipino benefits from cutting-edge medical services. Our goal is to integrate these advancements into the fabric of Filipino society, setting new paradigms of compassion, quality, and efficiency in healthcare, making a healthier life accessible to all.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-y-3">
                    <h2 class="font-bold">ASSET EXPRESS</h2>

                    <div class="flex flex-col gap-y-3 xl:flex-row xl:items-start xl:gap-x-6">
                        <div >
                            <img class="w-[60rem] xl:w-auto"
                                 src="/images/finance2.jpg" />
                        </div>
                        <div class="flex flex-col gap-y-3">
                            <div class="flex flex-col">
                                <h3 class="font-medium italic">Mission</h3>
                                <p class="text-justify">To empower individuals and businesses by providing strategic financial solutions that navigate the complexities of the modern economy. We specialize in real estate and chattel mortgages, bridge financing, and takeout loans, focusing on creating opportunities for growth and stability. Our mission is guided by a commitment to integrity, innovation, and personalized service, ensuring that every client achieves their financial goals with confidence.</p>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-medium italic">Vision</h3>
                                <p class="text-justify">Our vision is to become the most trusted and influential financial consultancy in the Philippines, known for our ability to deliver tailored solutions that meet the evolving needs of our clients. We aim to facilitate financial empowerment and economic growth for individuals and businesses alike, through expert advice, ethical practices, and a deep understanding of financial markets. By continuously adapting to the changing landscape and focusing on sustainable success, we aspire to make a lasting impact on the financial well-being of our clients and the community.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </BaseLayout>
</template>
