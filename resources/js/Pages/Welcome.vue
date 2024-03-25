<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

</script>

<template>
    <Head title="Welcome" />

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!--====== Required meta tags ======-->
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="description" content="" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <title>Startup landing page by TailGrids UI Components</title>
    </head>

    <body
        x-data="
      {
        scrolledFromTop: false
      }
    "
        x-init="window.pageYOffset >= 50 ? scrolledFromTop = true : scrolledFromTop = false"
        @scroll.window="window.pageYOffset >= 50 ? scrolledFromTop = true : scrolledFromTop = false"
    >
    <!-- ====== Navbar Section Start -->
    <header
        x-data="
        {
          navbarOpen: false,
        }
      "
        :class="scrolledFromTop ? 'fixed z-50 bg-white bg-opacity-80 shadow-sm backdrop-blur-sm' : 'absolute' "
        class="left-0 top-0 z-50 w-full"
    >
        <div class="container mx-auto">
            <div class="relative -mx-4 flex items-center justify-between">
                <div class="w-60 max-w-full px-4">
                    <a href="javascript:void(0)" class="block w-full py-5">
                        <img
                            src="assets/images/logo/logo.svg"
                            alt="logo"
                            class="w-full"
                        />
                    </a>
                </div>
                <div class="flex w-full items-center justify-between px-4">
                    <div>
                        <button
                            @click="navbarOpen = !navbarOpen"
                            :class="navbarOpen && 'navbarTogglerActive'"
                            id="navbarToggler"
                            class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-[6px] ring-primary focus:ring-2 lg:hidden"
                        >
                <span
                    class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"
                ></span>
                            <span
                                class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"
                            ></span>
                            <span
                                class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"
                            ></span>
                        </button>
                        <nav
                            x-transition
                            :class="!navbarOpen && 'hidden'"
                            id="navbarCollapse"
                            class="absolute right-4 top-full w-full max-w-[250px] rounded-lg bg-white py-5 px-6 shadow transition-all lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:shadow-none"
                        >
                            <ul class="blcok lg:flex">
                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        class="flex py-2 text-base font-medium text-dark hover:text-primary lg:ml-12 lg:inline-flex"
                                    >
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        class="flex py-2 text-base font-medium text-dark hover:text-primary lg:ml-12 lg:inline-flex"
                                    >
                                        Payment
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        class="flex py-2 text-base font-medium text-dark hover:text-primary lg:ml-12 lg:inline-flex"
                                    >
                                        Features
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="hidden justify-end pr-16 sm:flex lg:pr-0">
                        <a
                            href="javascript:void(0)"
                            class="py-3 px-7 text-base font-medium text-dark hover:text-primary"
                        >
                            Login
                        </a>
                        <a
                            href="javascript:void(0)"
                            class="rounded-lg bg-primary py-3 px-7 text-base font-medium text-white hover:bg-opacity-90"
                        >
                            Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ====== Navbar Section End -->

    <!-- ====== Hero Section Start -->
    <div class="relative bg-white pt-[120px] pb-[110px] lg:pt-[150px]">
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4 lg:w-5/12">
                    <div class="hero-content">
                        <h1 class="mb-3 text-4xl font-bold leading-snug text-dark sm:text-[42px] lg:text-[40px] xl:text-[42px]">
                            SimpliStocks : La Solution de Gestion de Stock pour Petits Commerces
                        </h1>
                        <p class="mb-8 max-w-[480px] text-base text-body-color">
                            Simplifiez votre gestion d'inventaire avec SimpliStocks, la plateforme conçue spécifiquement pour les besoins des petits commerces. Notre outil intuitif vous aide à suivre vos stocks et prévenir les ruptures ou surstocks sans effort.<br> Rejoignez les centaines de commerçants qui propulsent leur efficacité et réduisent leurs coûts grâce à SimpliStocks.
                        </p>

                        <ul class="flex flex-wrap items-center">
                            <li>
                                <a :href="route('filament.app.auth.register')"
                                    class="inline-flex items-center justify-center rounded-lg bg-primary py-4 px-6 text-center text-base font-normal text-white hover:bg-opacity-90 sm:px-10 lg:px-8 xl:px-10">
                                  Commencer
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="hidden px-4 lg:block lg:w-1/12"></div>
                <div class="w-full px-4 lg:w-6/12">
                    <div class="lg:ml-auto lg:text-right">
                        <div class="relative z-10 inline-block pt-11 lg:pt-0">
                            <img
                                src="/images/home/image1.jpg"
                                alt="SimpliStocks : La Solution de Gestion de Stock pour Petits Commerces"
                                class="max-w-full lg:ml-auto rounded-2xl shadow-xl"
                            />
                            <span class="absolute -left-8 -bottom-8 z-[-1]">
                  <svg
                      width="93"
                      height="93"
                      viewBox="0 0 93 93"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle cx="2.5" cy="2.5" r="2.5" fill="#3056D3" />
                    <circle cx="2.5" cy="24.5" r="2.5" fill="#3056D3" />
                    <circle cx="2.5" cy="46.5" r="2.5" fill="#3056D3" />
                    <circle cx="2.5" cy="68.5" r="2.5" fill="#3056D3" />
                    <circle cx="2.5" cy="90.5" r="2.5" fill="#3056D3" />
                    <circle cx="24.5" cy="2.5" r="2.5" fill="#3056D3" />
                    <circle cx="24.5" cy="24.5" r="2.5" fill="#3056D3" />
                    <circle cx="24.5" cy="46.5" r="2.5" fill="#3056D3" />
                    <circle cx="24.5" cy="68.5" r="2.5" fill="#3056D3" />
                    <circle cx="24.5" cy="90.5" r="2.5" fill="#3056D3" />
                    <circle cx="46.5" cy="2.5" r="2.5" fill="#3056D3" />
                    <circle cx="46.5" cy="24.5" r="2.5" fill="#3056D3" />
                    <circle cx="46.5" cy="46.5" r="2.5" fill="#3056D3" />
                    <circle cx="46.5" cy="68.5" r="2.5" fill="#3056D3" />
                    <circle cx="46.5" cy="90.5" r="2.5" fill="#3056D3" />
                    <circle cx="68.5" cy="2.5" r="2.5" fill="#3056D3" />
                    <circle cx="68.5" cy="24.5" r="2.5" fill="#3056D3" />
                    <circle cx="68.5" cy="46.5" r="2.5" fill="#3056D3" />
                    <circle cx="68.5" cy="68.5" r="2.5" fill="#3056D3" />
                    <circle cx="68.5" cy="90.5" r="2.5" fill="#3056D3" />
                    <circle cx="90.5" cy="2.5" r="2.5" fill="#3056D3" />
                    <circle cx="90.5" cy="24.5" r="2.5" fill="#3056D3" />
                    <circle cx="90.5" cy="46.5" r="2.5" fill="#3056D3" />
                    <circle cx="90.5" cy="68.5" r="2.5" fill="#3056D3" />
                    <circle cx="90.5" cy="90.5" r="2.5" fill="#3056D3" />
                  </svg>
                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Hero Section End -->

    <!-- ====== Services Section Start -->
    <section class="pt-20 pb-12 lg:pt-[120px] lg:pb-[90px]">
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4">
                    <div class="mx-auto mb-12 max-w-[510px] text-center lg:mb-20">
                    <span class="mb-2 block text-lg font-semibold text-primary">
                        Découvrez Nos Solutions
                    </span>
                        <h2 class="mb-4 text-3xl font-bold text-dark sm:text-4xl md:text-[40px]">
                            Nos Services Exclusifs
                        </h2>
                        <p class="text-base text-body-color">
                            SimpliStocks offre une gamme de solutions innovantes pour la gestion d'inventaire adaptées aux petits commerces. Nos services sont conçus pour maximiser votre efficacité et réduire les coûts. Découvrez comment nous pouvons transformer votre gestion de stock.
                        </p>
                    </div>
                </div>
            </div>
            <div class="-mx-4 flex flex-wrap justify-center">
                <div class="w-full px-4 sm:w-4/5 md:w-1/2 lg:w-1/3">
                    <div class="group relative mb-8 rounded-xl border border-[#EFEFEF] bg-white p-10 text-center md:px-8 lg:py-9 lg:px-6 xl:p-10 2xl:p-12">
                        <div class="relative z-10 mx-auto mb-10 inline-flex h-11 items-center">
                            <span class="absolute -right-3 top-0 z-[-1] h-7 w-7 rounded-full bg-primary opacity-20"></span>
                            <svg
                                width="49"
                                height="35"
                                viewBox="0 0 49 35"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M46.7188 20.1553H44.9219C44.2188 20.1553 43.5938 19.3741 43.5938 18.4366V6.56157C43.5938 5.54594 44.2188 4.76469 45 4.76469H46.7188C47.5 4.76469 48.125 4.13969 48.125 3.35844C48.125 2.57719 47.5 2.03032 46.7188 2.03032H44.9219C43.4375 2.03032 42.1094 2.96782 41.3281 4.29594H30.9375L29.9219 3.43657C25.7812 -0.157184 19.6094 -0.547809 15.3125 2.57719C13.4375 3.98344 12.1094 5.62407 11.4844 6.48344C11.4844 6.56157 11.4062 6.56157 11.4062 6.56157H9.60938V6.48344C9.60938 3.98344 7.8125 2.03032 5.54688 2.03032H1.40625C0.625 2.03032 0 2.65532 0 3.43657C0 4.21782 0.625 4.76469 1.40625 4.76469H5.46875C6.17188 4.76469 6.79688 5.54594 6.79688 6.48344V18.2803C6.79688 19.2959 6.17188 20.0772 5.39063 20.0772H1.40625C0.625 20.0772 0 20.7022 0 21.4834C0 22.2647 0.625 22.8897 1.40625 22.8897H5.46875C6.95312 22.8897 8.28125 21.9522 9.0625 20.6241H9.6875C10.3906 20.6241 11.0156 20.8584 11.6406 21.1709C11.5625 21.5616 11.5625 22.0303 11.875 22.3428L13.6719 24.7647C13.8281 24.9991 14.1406 25.1553 14.375 25.2334L16.875 25.9366L17.9688 28.2803C18.125 28.6709 18.4375 28.9053 18.8281 28.9834L21.4062 29.6866L23.0469 32.3428C23.2031 32.6553 23.5156 32.8116 23.8281 32.9678L28.2031 34.2178C28.3594 34.2178 28.4375 34.2959 28.5937 34.2959C29.0625 34.2959 29.4531 34.0616 29.7656 33.6709L38.5156 20.7022C38.5938 20.7022 38.5938 20.6241 38.75 20.6241H41.4844C42.1875 21.9522 43.5156 22.8897 45 22.8897H46.7188C47.5 22.8897 48.125 22.2647 48.125 21.4834C48.125 20.7022 47.5 20.1553 46.7188 20.1553ZM13.3594 19.0616C12.2656 18.2803 11.0156 17.8897 9.6875 17.8897H9.60938V9.29594H11.4062C12.3438 9.29594 13.2031 8.82719 13.75 8.12407C14.2969 7.42094 15.3906 6.01469 16.9531 4.84282C20.2344 2.42094 24.9219 2.73344 28.2031 5.54594L30.8594 7.88969C31.3281 8.28032 31.6406 8.82719 31.6406 9.21782C31.6406 9.53032 31.5625 9.76469 31.3281 9.99907L30 11.4053C29.2187 12.2647 27.9687 12.3428 27.0312 11.6397L26.7188 11.4053C24.7656 9.92094 22.1094 10.1553 20.3906 11.8741L13.3594 19.0616ZM36.4844 18.7491C36.4062 18.8272 36.3281 18.9053 36.3281 18.9834L27.9688 31.3272L25.0781 30.5459L23.4375 27.8897C23.2812 27.5772 22.9688 27.4209 22.6562 27.2647L20.1562 26.5616L19.0625 24.2178C18.9062 23.8272 18.5938 23.5928 18.2031 23.5147L15.5469 22.8116L14.6875 21.6397L22.3437 13.7491C23.125 13.0459 24.2187 12.9678 25.0781 13.5928L25.3906 13.8272C27.4219 15.3116 30.2344 15.0772 31.9531 13.2803L33.2812 11.8741C33.9844 11.0928 34.375 10.0772 34.2969 9.06157C34.2969 8.35844 34.0625 7.73344 33.6719 7.10844H40.7812V17.9678H38.5938C37.8125 17.8897 37.0312 18.2022 36.4844 18.7491Z"
                                    fill="#3056D3"
                                />
                            </svg>
                        </div>
                        <h4 class="mb-3 text-xl font-semibold text-dark">
                            Gestion des produits
                        </h4>
                        <p class="text-body-color">
                            Gérer vos produits n'a jamais été aussi simple. SimpliStocks vous permet de suivre vos stocks.
                        </p>

                        <span class="absolute -bottom-2 left-0 right-0 z-[-1] mx-auto h-12 w-3/4 bg-primary opacity-0 blur-[12px] transition group-hover:opacity-[14%]"></span>
                    </div>
                </div>
                <div class="w-full px-4 sm:w-4/5 md:w-1/2 lg:w-1/3">
                    <div class="group relative mb-8 rounded-xl border border-[#EFEFEF] bg-white p-10 text-center md:px-8 lg:py-9 lg:px-6 xl:p-10 2xl:p-12">
                        <div class="relative z-10 mx-auto mb-10 inline-flex h-11 items-center">
                            <span class="absolute -right-3 top-0 z-[-1] h-7 w-7 rounded-full bg-primary opacity-20"></span>
                            <svg
                                width="45"
                                height="48"
                                viewBox="0 0 45 48"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M43.827 35.6532C42.8929 32.6172 39.8569 30.8268 36.8988 31.6831C34.0963 32.5394 30.2819 33.6292 27.4795 34.4855C27.8687 33.3957 27.713 32.5394 27.4795 31.8388C26.8567 30.204 24.9884 29.4256 23.4315 29.4256H15.8026C15.102 29.4256 14.4014 29.1142 13.7008 28.4136C12.9224 27.713 11.9104 27.3237 10.8205 27.3237H4.67073C2.10183 27.3237 0 29.5034 0 32.1502V41.9587C0 44.6833 2.10183 46.863 4.74858 46.863H12.1439C13.078 46.863 14.0122 46.5516 14.7907 46.0067L14.9463 46.0845C17.0482 47.2522 19.4614 47.875 21.8746 47.875C23.3537 47.875 24.8327 47.6414 26.1561 47.2522L39.9348 43.0485H40.0126C43.9049 41.5695 44.5276 38.1443 43.827 35.6532ZM4.74858 44.1384C3.65874 44.1384 2.72459 43.1264 2.72459 41.9587V32.1502C2.72459 30.9825 3.58089 30.0483 4.67073 30.0483H10.8205C11.2876 30.0483 11.6768 30.2819 11.8325 30.4376C12.0661 30.6711 12.3774 30.9046 12.611 31.0603V44.0605C12.4553 44.1384 12.2996 44.1384 12.1439 44.1384H4.74858ZM39.0785 40.4796L25.3776 44.6833C24.2878 44.9947 23.1201 45.2282 21.9524 45.2282C20.0063 45.2282 18.0602 44.7611 16.2697 43.7491L15.3356 43.2821V32.1502C15.4913 32.1502 15.647 32.1502 15.8026 32.1502H23.4315C24.1321 32.1502 24.8327 32.4615 24.9884 32.8508C25.1441 33.3178 24.8327 34.3298 23.2758 35.8089C22.8087 36.276 22.7309 36.8987 23.0423 37.4436C23.5872 38.4556 23.5872 38.4556 31.1382 36.1981C34.4077 35.264 37.6772 34.252 37.6772 34.252C39.1563 33.8628 40.7132 34.7191 41.1803 36.3538C41.4917 37.5215 41.6474 39.5455 39.0785 40.4796Z"
                                    fill="#3056D3"
                                />
                                <path
                                    d="M36.4317 11.0541C39.4677 11.0541 41.9588 8.56301 41.9588 5.52703C41.9588 2.49106 39.4677 0 36.4317 0C33.3957 0 30.9047 2.49106 30.9047 5.52703C30.9047 8.56301 33.4736 11.0541 36.4317 11.0541ZM36.4317 2.80244C37.9886 2.80244 39.2342 4.04797 39.2342 5.60488C39.2342 7.16179 37.9886 8.40732 36.4317 8.40732C34.8748 8.40732 33.6293 7.16179 33.6293 5.60488C33.7071 3.97012 34.9527 2.80244 36.4317 2.80244Z"
                                    fill="#3056D3"
                                />
                                <path
                                    d="M34.6412 27.9467H37.2102C40.2461 27.9467 42.6593 25.5334 42.6593 22.4975V18.0603C42.6593 15.0243 40.2461 12.6111 37.2102 12.6111H34.8748C31.8388 12.6111 29.4256 15.0243 29.4256 18.0603V22.4975C29.5034 25.5334 31.8388 27.9467 34.6412 27.9467ZM32.1502 18.0603C32.1502 16.5812 33.3957 15.3357 34.8748 15.3357H37.2102C38.6892 15.3357 39.9347 16.5812 39.9347 18.0603V22.4975C39.9347 23.9765 38.6892 25.2221 37.2102 25.2221H34.6412C33.3179 25.2221 32.1502 23.9765 32.1502 22.4196V18.0603Z"
                                    fill="#3056D3"
                                />
                            </svg>
                        </div>
                        <h4 class="mb-3 text-xl font-semibold text-dark">
                            Gestion du stock
                        </h4>
                        <p class="text-body-color">
                            Planifier des alertes de stock, suivre les mouvements de stock afin de prévenir les ruptures ou surstocks.
                        </p>
                        <span
                            class="absolute -bottom-2 left-0 right-0 z-[-1] mx-auto h-12 w-3/4 bg-primary opacity-0 blur-[12px] transition group-hover:opacity-[14%]"
                        ></span>
                    </div>
                </div>
                <div class="w-full px-4 sm:w-4/5 md:w-1/2 lg:w-1/3">
                    <div class="group relative mb-8 rounded-xl border border-[#EFEFEF] bg-white p-10 text-center md:px-8 lg:py-9 lg:px-6 xl:p-10 2xl:p-12">
                        <div class="relative z-10 mx-auto mb-10 inline-flex h-11 items-center">
                <span class="absolute -right-3 top-0 z-[-1] h-7 w-7 rounded-full bg-primary opacity-20"></span>
                            <svg
                                width="39"
                                height="35"
                                viewBox="0 0 39 35"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M36.5897 11.4805H31.6252V1.8617C31.6252 0.868794 30.8184 0 29.7635 0H5.06489C4.07198 0 3.26524 0.806738 3.26524 1.8617V15.0177C2.83084 15.266 2.52056 15.7004 2.39645 16.2589L0.0382918 27.8635C-0.0858217 28.422 0.100349 28.9805 0.472689 29.4149C0.845029 29.8493 1.34148 30.0975 1.89999 30.0975H22.9372V33.4486C22.9372 34.3174 23.6199 35 24.4886 35H36.6518C37.5206 35 38.2032 34.3174 38.2032 33.4486V13.0319C38.2032 12.2252 37.4585 11.4805 36.5897 11.4805ZM2.27233 27.8635L4.50638 16.9415H5.12694H22.9372V27.8635H2.27233ZM22.9372 13.0319V14.7695H5.43723V2.23404H29.4532V11.5426H24.4886C23.6199 11.4805 22.9372 12.2252 22.9372 13.0319ZM36.0312 32.766H25.1092V13.6525H36.0312V32.766Z"
                                    fill="#3056D3"
                                />
                                <path
                                    d="M30.5705 28.7322C29.7638 28.7322 29.1432 29.3527 29.1432 30.1595C29.1432 30.9662 29.7638 31.5868 30.5705 31.5868C31.3772 31.5868 31.9978 30.9662 31.9978 30.1595C31.9978 29.3527 31.3152 28.7322 30.5705 28.7322Z"
                                    fill="#3056D3"
                                />
                                <path
                                    d="M29.143 18.4308H32.6802C33.3008 18.4308 33.7972 17.9343 33.7972 17.3138C33.7972 16.6932 33.2387 16.2588 32.6802 16.2588H29.143C28.5224 16.2588 28.0259 16.7552 28.0259 17.3758C28.0259 17.9964 28.5224 18.4308 29.143 18.4308Z"
                                    fill="#3056D3"
                                />
                            </svg>
                        </div>
                        <h4 class="mb-3 text-xl font-semibold text-dark">
                           Gestion des commerces
                        </h4>
                        <p class="text-body-color">
                            Gérer vos commerces n'a jamais été aussi simple. SimpliStocks vous permet de suivre vos stocks sur vos différents commerces.
                        </p>

                        <span class="absolute -bottom-2 left-0 right-0 z-[-1] mx-auto h-12 w-3/4 bg-primary opacity-0 blur-[12px] transition group-hover:opacity-[14%]"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Services Section End -->

    <!-- ====== Pricing Section Start -->
    <section class="relative z-20 overflow-hidden bg-white pt-20 pb-12 lg:pt-[120px] lg:pb-[90px]">
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4">
                    <div class="mx-auto mb-[60px] max-w-[510px] text-center lg:mb-20">
                        <span class="mb-2 block text-lg font-semibold text-primary">
                            Notre Tarif
                        </span>
                        <h2 class="mb-4 text-3xl font-bold text-dark sm:text-4xl md:text-[40px]">
                            Un Plan Unique, Une Gestion Simplifiée
                        </h2>
                        <p class="text-base text-body-color">
                            SimpliStocks vous propose un plan tarifaire unique, conçu pour répondre efficacement à tous vos besoins de gestion de stock, sans complexité ni coûts cachés. Profitez d'une solution complète de gestion d'inventaire avec un abonnement mensuel abordable, incluant la gestion des produits, les alertes de stock, et bien plus. Optimisez votre gestion de stock dès aujourd'hui avec SimpliStocks.
                        </p>
                    </div>


                </div>
            </div>

            <div class="-mx-4 flex flex-wrap justify-center">
                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div class="relative z-10 mb-10 overflow-hidden rounded-xl border border-primary border-opacity-20 bg-white py-10 px-8 shadow-pricing sm:p-12 lg:py-10 lg:px-6 xl:p-12">
                          <span class="mb-4 block text-lg font-semibold text-primary">
                            Abonnement Classique
                          </span>
                        <h2 class="mb-5 text-[42px] font-bold text-dark">
                            20€
                            <span class="text-base font-medium text-body-color">
                          / mois
                        </span>
                        </h2>
                        <p class="mb-8 border-b border-[#F2F2F2] pb-8 text-base text-body-color">
                            <b>Abonnement mensuel</b> pour un commerce
                        </p>
                        <div class="mb-7">
                            <p class="mb-1 text-base leading-loose text-body-color">
                                <b>7 jours d'essai gratuit</b>
                            </p>
                            <p class="mb-1 text-base leading-loose text-body-color">
                                Utilisateurs illimités
                            </p>
                            <p class="mb-1 text-base leading-loose text-body-color">
                                Accès complet à toutes les fonctionnalités
                            </p>
                            <p class="mb-1 text-base leading-loose text-body-color">
                                Mise à jour gratuite
                            </p>
                            <p class="mb-1 text-base leading-loose text-body-color">
                                Simple et facile à utiliser
                            </p>
                        </div>
                        <a :href="route('filament.app.auth.register')"
                            class="block w-full rounded-md border border-primary bg-primary p-4 text-center text-base font-semibold text-white transition hover:bg-opacity-90">
                            Commencer
                        </a>

                        <div>
                <span class="absolute right-0 top-7 z-[-1]">
                  <svg
                      width="77"
                      height="172"
                      viewBox="0 0 77 172"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle cx="86" cy="86" r="86" fill="url(#paint0_linear)" />
                    <defs>
                      <linearGradient
                          id="paint0_linear"
                          x1="86"
                          y1="0"
                          x2="86"
                          y2="172"
                          gradientUnits="userSpaceOnUse"
                      >
                        <stop stop-color="#3056D3" stop-opacity="0.09" />
                        <stop
                            offset="1"
                            stop-color="#C4C4C4"
                            stop-opacity="0"
                        />
                      </linearGradient>
                    </defs>
                  </svg>
                </span>
                            <span class="absolute right-4 top-4 z-[-1]">
                  <svg
                      width="41"
                      height="89"
                      viewBox="0 0 41 89"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle
                        cx="38.9138"
                        cy="87.4849"
                        r="1.42021"
                        transform="rotate(180 38.9138 87.4849)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="74.9871"
                        r="1.42021"
                        transform="rotate(180 38.9138 74.9871)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="62.4892"
                        r="1.42021"
                        transform="rotate(180 38.9138 62.4892)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="38.3457"
                        r="1.42021"
                        transform="rotate(180 38.9138 38.3457)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="13.634"
                        r="1.42021"
                        transform="rotate(180 38.9138 13.634)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="50.2754"
                        r="1.42021"
                        transform="rotate(180 38.9138 50.2754)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="26.1319"
                        r="1.42021"
                        transform="rotate(180 38.9138 26.1319)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="38.9138"
                        cy="1.42021"
                        r="1.42021"
                        transform="rotate(180 38.9138 1.42021)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="87.4849"
                        r="1.42021"
                        transform="rotate(180 26.4157 87.4849)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="74.9871"
                        r="1.42021"
                        transform="rotate(180 26.4157 74.9871)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="62.4892"
                        r="1.42021"
                        transform="rotate(180 26.4157 62.4892)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="38.3457"
                        r="1.42021"
                        transform="rotate(180 26.4157 38.3457)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="13.634"
                        r="1.42021"
                        transform="rotate(180 26.4157 13.634)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="50.2754"
                        r="1.42021"
                        transform="rotate(180 26.4157 50.2754)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="26.1319"
                        r="1.42021"
                        transform="rotate(180 26.4157 26.1319)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="26.4157"
                        cy="1.4202"
                        r="1.42021"
                        transform="rotate(180 26.4157 1.4202)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="87.4849"
                        r="1.42021"
                        transform="rotate(180 13.9177 87.4849)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="74.9871"
                        r="1.42021"
                        transform="rotate(180 13.9177 74.9871)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="62.4892"
                        r="1.42021"
                        transform="rotate(180 13.9177 62.4892)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="38.3457"
                        r="1.42021"
                        transform="rotate(180 13.9177 38.3457)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="13.634"
                        r="1.42021"
                        transform="rotate(180 13.9177 13.634)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="50.2754"
                        r="1.42021"
                        transform="rotate(180 13.9177 50.2754)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="26.1319"
                        r="1.42021"
                        transform="rotate(180 13.9177 26.1319)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="13.9177"
                        cy="1.42019"
                        r="1.42021"
                        transform="rotate(180 13.9177 1.42019)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="87.4849"
                        r="1.42021"
                        transform="rotate(180 1.41963 87.4849)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="74.9871"
                        r="1.42021"
                        transform="rotate(180 1.41963 74.9871)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="62.4892"
                        r="1.42021"
                        transform="rotate(180 1.41963 62.4892)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="38.3457"
                        r="1.42021"
                        transform="rotate(180 1.41963 38.3457)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="13.634"
                        r="1.42021"
                        transform="rotate(180 1.41963 13.634)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="50.2754"
                        r="1.42021"
                        transform="rotate(180 1.41963 50.2754)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="26.1319"
                        r="1.42021"
                        transform="rotate(180 1.41963 26.1319)"
                        fill="#3056D3"
                    />
                    <circle
                        cx="1.41963"
                        cy="1.4202"
                        r="1.42021"
                        transform="rotate(180 1.41963 1.4202)"
                        fill="#3056D3"
                    />
                  </svg>
                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Pricing Section End -->

    <!-- ====== FAQ Section Start -->
    <section
        x-data="
        {
          openFaq1: false,
          openFaq2: false,
          openFaq3: false,
          openFaq4: false,
          openFaq5: false,
          openFaq6: false
        }
      "
        class="relative z-20 overflow-hidden bg-white pt-20 pb-12 lg:pt-[120px] lg:pb-[90px]"
    >
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4">
                    <div class="mx-auto mb-[60px] max-w-[520px] text-center lg:mb-20">
                    <span class="mb-2 block text-lg font-semibold text-primary">
                        FAQ SimpliStocks
                    </span>
                        <h2 class="mb-4 text-3xl font-bold text-dark sm:text-4xl md:text-[40px]">
                            Vos Questions Fréquentes
                        </h2>
                        <p class="text-base text-body-color">
                            Vous avez des questions sur la manière dont SimpliStocks peut transformer votre gestion de stock ? Découvrez les réponses aux questions fréquentes sur notre solution intuitive de gestion d'inventaire, conçue pour les petits commerces. De la mise en place à l'utilisation quotidienne, nous sommes là pour vous aider.
                        </p>
                    </div>

                </div>
            </div>

            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4 lg:w-1/2">
                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Quand puis-je commencer à utiliser SimpliStocks après mon inscription ?
                            </h4>
                        </div>
                        <div class="faq-content">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                Vous pouvez commencer à utiliser SimpliStocks immédiatement après votre inscription et souscription. Notre plateforme SaaS basée sur le navigateur web est conçue pour vous permettre un accès instantané à tous nos outils de gestion de stock. Il n'y a aucun délai d'attente pour l'installation ou la configuration initiale - vous êtes prêt à optimiser votre inventaire dès que vous vous connectez pour la première fois.
                            </p>
                        </div>
                    </div>


                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Combien de temps après mon inscription puis-je gérer mon stock ?
                            </h4>
                        </div>
                        <div class="faq-content">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                Dès votre première utilisation de SimpliStocks, vous commencez à bénéficier de nos analyses. Notre plateforme est conçue pour vous fournir des insights précieux sur votre inventaire immédiatement après la saisie de vos données de stock. Aucune attente n'est nécessaire : commencez à optimiser votre gestion d'inventaire dès le premier jour.
                            </p>
                        </div>
                    </div>

                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Comment SimpliStocks assure-t-il la sécurité de mes données d'inventaire ?
                            </h4>
                        </div>
                        <div class="faq-content">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                La sécurité de vos données est notre priorité absolue chez SimpliStocks. Nous utilisons des technologies de cryptage de pointe et des protocoles de sécurité stricts pour protéger toutes les informations de votre inventaire.
                            </p>
                        </div>
                    </div>

                </div>
                <div class="w-full px-4 lg:w-1/2">
                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Que puis-je attendre des versions futures de SimpliStocks et comment gérez-vous les bugs ?
                            </h4>
                        </div>
                        <div class="faq-content">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                SimpliStocks est actuellement toujours en développement, ce qui signifie que nous sommes en phase active de développement et d'amélioration. Nous travaillons sans relâche pour ajouter de nouvelles fonctionnalités qui rendront la gestion de votre inventaire encore plus facile et intuitive. Bien que cette phase puisse inclure des bugs, notre équipe s'engage à les identifier et les corriger rapidement. Nous encourageons les retours de nos utilisateurs pour aider à améliorer constamment SimpliStocks. Votre feedback est précieux et joue un rôle crucial dans le développement de futures versions plus robustes et riches en fonctionnalités.
                            </p>
                        </div>
                    </div>
                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Comment SimpliStocks accompagne-t-il les nouveaux utilisateurs dans leur prise en main ?
                            </h4>
                        </div>
                        <div class="faq-content ">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                Chez SimpliStocks, nous comprenons l'importance d'une prise en main efficace pour maximiser les bénéfices de notre outil de gestion de stock dès le premier jour. Nous nous engageons à vous fournir l'assistance nécessaire pour assurer une expérience fluide et intuitive, vous permettant ainsi de tirer le meilleur parti de SimpliStocks sans délai.
                            </p>
                        </div>
                    </div>
                    <div class="single-faq mb-8 w-full rounded-lg border border-[#F3F4FE] bg-white p-4 sm:p-8 lg:px-6 xl:px-8">
                        <div class="w-full">
                            <h4 class="text-lg font-semibold text-black">
                                Que faire si je rencontre un bug ou un problème avec SimpliStocks ?
                            </h4>
                        </div>
                        <div class="faq-content">
                            <p class="py-3 text-base leading-relaxed text-body-color">
                                Si vous rencontrez un bug ou un problème dans l'utilisation de SimpliStocks, nous vous encourageons à le signaler immédiatement afin que nous puissions y remédier rapidement. Vous pouvez nous contacter directement par email. Nous prenons chaque signalement au sérieux et nous nous engageons à fournir une réponse et une solution dans les plus brefs délais. Votre aide est inestimable pour nous aider à améliorer SimpliStocks et à vous offrir une expérience utilisateur optimale.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="absolute bottom-0 right-0 z-[-1]">
            <svg
                width="1440"
                height="886"
                viewBox="0 0 1440 886"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    opacity="0.5"
                    d="M193.307 -273.322L1480.87 1014.24L1121.85 1373.26C1121.85 1373.26 731.745 983.23 478.513 729.926C225.976 477.316 -165.714 85.6985 -165.714 85.6985L193.307 -273.322Z"
                    fill="url(#paint0_linear_1314_168)"
                />
                <defs>
                    <linearGradient
                        id="paint0_linear_1314_168"
                        x1="1308.65"
                        y1="1142.58"
                        x2="602.827"
                        y2="-418.682"
                        gradientUnits="userSpaceOnUse"
                    >
                        <stop stop-color="#3056D3" stop-opacity="0.36" />
                        <stop offset="1" stop-color="#F5F2FD" stop-opacity="0" />
                        <stop offset="1" stop-color="#F5F2FD" stop-opacity="0.096144" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </section>
    <!-- ====== FAQ Section End -->

    <!-- ====== Contact Section Start -->
    <section class="relative z-10 overflow-hidden bg-white py-20 lg:py-[120px]">
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap lg:justify-between">
                <div class="w-full px-4 lg:w-1/2 xl:w-6/12">
                    <div class="mb-12 max-w-[570px] lg:mb-0">
              <span class="mb-4 block text-base font-semibold text-primary">
                Contactez-nous
              </span>
                        <h2 class="mb-6 text-[32px] font-bold uppercase text-dark sm:text-[40px] lg:text-[36px] xl:text-[40px]">
                            Vous avez une question ?
                        </h2>
                        <p class="mb-9 text-base leading-relaxed text-body-color">
                            Notre équipe est là pour vous aider. Contactez-nous pour toute question, suggestion ou demande d'assistance. Nous sommes disponibles pour répondre à vos questions et vous accompagner dans l'utilisation de SimpliStocks.
                        </p>
                    </div>
                </div>
                <div class="w-full px-4 lg:w-1/2 xl:w-5/12">
                    <div class="relative rounded-lg bg-white p-8 shadow-lg sm:p-12">
                        <form>
                            <div class="mb-6">
                                <input
                                    type="text"
                                    placeholder="Your Name"
                                    class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
                                />
                            </div>
                            <div class="mb-6">
                                <input
                                    type="email"
                                    placeholder="Your Email"
                                    class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
                                />
                            </div>
                            <div class="mb-6">
                                <input
                                    type="text"
                                    placeholder="Your Phone"
                                    class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
                                />
                            </div>
                            <div class="mb-6">
                      <textarea
                          rows="6"
                          placeholder="Your Message"
                          class="w-full resize-none rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"></textarea>
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    class="w-full rounded border border-primary bg-primary p-3 text-white transition hover:bg-opacity-90">
                                    Envoyer
                                </button>
                            </div>
                        </form>
                        <div>
                <span class="absolute -top-10 -right-9 z-[-1]">
                  <svg
                      width="100"
                      height="100"
                      viewBox="0 0 100 100"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0 100C0 44.7715 0 0 0 0C55.2285 0 100 44.7715 100 100C100 100 100 100 0 100Z"
                        fill="#3056D3"
                    />
                  </svg>
                </span>
                            <span class="absolute -right-10 top-[90px] z-[-1]">
                  <svg
                      width="34"
                      height="134"
                      viewBox="0 0 34 134"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle
                        cx="31.9993"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 31.9993 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 31.9993 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 31.9993 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 31.9993 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 31.9993 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 31.9993 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 31.9993 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 31.9993 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 31.9993 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 31.9993 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 17.3333 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 17.3333 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 17.3333 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 17.3333 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 17.3333 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 17.3333 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 17.3333 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 17.3333 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 17.3333 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 17.3333 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 2.66536 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 2.66536 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 2.66536 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 2.66536 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 2.66536 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 2.66536 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 2.66536 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 2.66536 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 2.66536 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 2.66536 1.66665)"
                        fill="#13C296"
                    />
                  </svg>
                </span>
                            <span class="absolute -left-7 -bottom-7 z-[-1]">
                  <svg
                      width="107"
                      height="134"
                      viewBox="0 0 107 134"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle
                        cx="104.999"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 104.999 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 104.999 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 104.999 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 104.999 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 104.999 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 104.999 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 104.999 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 104.999 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 104.999 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="104.999"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 104.999 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 90.3333 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 90.3333 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 90.3333 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 90.3333 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 90.3333 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 90.3333 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 90.3333 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 90.3333 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 90.3333 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="90.3333"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 90.3333 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 75.6654 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 31.9993 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 75.6654 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 31.9993 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 75.6654 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 31.9993 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 75.6654 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 31.9993 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 75.6654 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 31.9993 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 75.6654 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 31.9993 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 75.6654 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 31.9993 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 75.6654 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 31.9993 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 75.6654 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 31.9993 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="75.6654"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 75.6654 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="31.9993"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 31.9993 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 60.9993 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 17.3333 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 60.9993 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 17.3333 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 60.9993 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 17.3333 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 60.9993 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 17.3333 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 60.9993 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 17.3333 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 60.9993 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 17.3333 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 60.9993 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 17.3333 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 60.9993 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 17.3333 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 60.9993 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 17.3333 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="60.9993"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 60.9993 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="17.3333"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 17.3333 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 46.3333 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="132"
                        r="1.66667"
                        transform="rotate(180 2.66536 132)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 46.3333 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="117.333"
                        r="1.66667"
                        transform="rotate(180 2.66536 117.333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 46.3333 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="102.667"
                        r="1.66667"
                        transform="rotate(180 2.66536 102.667)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 46.3333 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="88"
                        r="1.66667"
                        transform="rotate(180 2.66536 88)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 46.3333 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="73.3333"
                        r="1.66667"
                        transform="rotate(180 2.66536 73.3333)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 46.3333 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="45"
                        r="1.66667"
                        transform="rotate(180 2.66536 45)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 46.3333 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="16"
                        r="1.66667"
                        transform="rotate(180 2.66536 16)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 46.3333 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="59"
                        r="1.66667"
                        transform="rotate(180 2.66536 59)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 46.3333 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="30.6666"
                        r="1.66667"
                        transform="rotate(180 2.66536 30.6666)"
                        fill="#13C296"
                    />
                    <circle
                        cx="46.3333"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 46.3333 1.66665)"
                        fill="#13C296"
                    />
                    <circle
                        cx="2.66536"
                        cy="1.66665"
                        r="1.66667"
                        transform="rotate(180 2.66536 1.66665)"
                        fill="#13C296"
                    />
                  </svg>
                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Contact Section End -->

    <!-- ====== Footer Section Start -->
    <footer class="relative z-10 bg-primary">


        <div class="mt-12 bg-[#101541] py-8 lg:mt-[60px]">
            <div class="container mx-auto">
                <div class="-mx-4 flex flex-wrap">
                    <div class="w-full px-4 md:w-1/3 lg:w-1/2">
                        <div class="my-1 flex justify-center md:justify-start">
                            <p class="text-base text-[#efefef]">&copy;  SimpliStocks</p>
                        </div>
                    </div>
                    <div class="w-full px-4 md:w-2/3 lg:w-1/2">
                        <div class="my-1">
                            <div
                                class="-mx-3 flex flex-wrap items-center justify-center md:justify-end">
                                <a
                                    href="javascript:void(0)"
                                    class="px-3 text-base text-[#efefef] hover:text-white">

                                </a>
                                <a
                                    href="javascript:void(0)"
                                    class="px-3 text-base text-[#efefef] hover:text-white"
                                >
                                   Notice Légale
                                </a>
                                <a
                                    href="javascript:void(0)"
                                    class="px-3 text-base text-[#efefef] hover:text-white">
                                    Conditions Générales de Vente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ====== Footer Section End -->
    </body>
    </html>

</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
