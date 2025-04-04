<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
    isAuth: {
        type: Boolean,
    },
});

const navbarOpen = ref(false);
</script>

<template>
    <Head title="Mentions légales" />
    <header class="bg-[#003366]">
        <div class="container mx-auto">
            <div class="relative -mx-4 flex items-center justify-between">
                <div>
                    <a href="/">
                        <ApplicationLogo class="h-24" />
                        <span class="sr-only">Quantisys</span>
                    </a>
                </div>
                <div class="flex w-full items-center justify-between px-4">
                    <div>
                        <button
                            @click="navbarOpen = !navbarOpen"
                            :class="navbarOpen && 'navbarTogglerActive'"
                            id="navbarToggler"
                            class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-[6px] ring-primary focus:ring-2 lg:hidden">
                            <span class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"></span>
                            <span class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"></span>
                            <span class="relative my-[6px] block h-[2px] w-[30px] bg-body-color"></span>
                        </button>
                        <nav
                            :class="!navbarOpen"
                            id="navbarCollapse"
                            class="absolute right-4 top-full w-full max-w-[250px] rounded-lg bg-[#5AB9EA] py-5 px-6 shadow transition-all lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:shadow-none"
                        >
                            <ul class="blcok lg:flex">
                                <li>
                                    <a
                                        href="/#payment"
                                        class="flex py-2 text-base font-medium text-white hover:text-[#5AB9EA] lg:ml-12 lg:inline-flex">
                                        Détails sur l'abonnement
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="/#faq"
                                        class="flex py-2 text-base font-medium text-white hover:text-[#5AB9EA] lg:ml-12 lg:inline-flex">
                                        Foires aux questions
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="/#contact"
                                        class="flex py-2 text-base font-medium text-white hover:text-[#5AB9EA] lg:ml-12 lg:inline-flex">
                                        Contactez-nous
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div v-if="!isAuth" class="hidden justify-end pr-16 sm:flex lg:pr-0">
                        <a :href="route('filament.app.auth.login')"
                           class="py-3 px-7 text-base font-medium text-white hover:text-[#5AB9EA]">
                            Connexion
                        </a>
                        <a :href="route('filament.app.auth.register')"
                           class="rounded-lg bg-[#5AB9EA] py-3 px-7 text-base font-medium text-white hover:bg-opacity-90">
                            S'inscrire
                        </a>
                    </div>
                    <div v-else class="hidden justify-end pr-16 sm:flex lg:pr-0">
                        <a href="/app"
                           class="rounded-lg bg-[#5AB9EA] py-3 px-7 text-base font-medium text-white hover:bg-opacity-90">
                            <span class="btn-txt">Accéder à l'application</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ====== Navbar Section End -->

    <!-- ====== Hero Section Start -->
    <div class="relative bg-white pt-[20px] pb-[110px] lg:pt-[50px]">
        <div class="container mx-auto">
            <div class="-mx-4 flex flex-wrap justify-center">
                <div class="w-full px-4 lg:w-10/12 text-center">
                    <div class="hero-content">
                        <h1 class="mb-3 text-4xl font-bold leading-snug text-dark sm:text-[42px] lg:text-[40px] xl:text-[42px]">
                            Mentions Légales
                        </h1>
                        <div class="w-full px-4">
                            <div class="hero-content text-center flex justify-center">
                                <p class="mb-8 max-w-[680px] text-base text-body-color">
                                    <strong>1. Informations légales</strong><br>
                                    <strong>Propriétaire du site :</strong><br>
                                    MR Cédric Lima, <br>Résidant 5 route de pouzoum 33138 LANTON <br>Nationalité Française (France), né(e) le 08/02/1991 <br>
                                    Web Discovery<br>
                                    Numéro SIRET : 84396946000016<br>
                                    Site web : <a href="https://www.quantisys.fr">www.Quantisys.fr</a><br><br>

                                    <strong>Directeur de la publication :</strong><br>
                                    MR LIMA CEDRIC PAULO<br>
                                    Contact : <a href="mailto:contact@Quantisys.com">contact@Quantisys.com</a><br><br>

                                    <strong>Hébergeur du site :</strong><br>
                                    OVH SAS<br>
                                    2 rue Kellermann<br>
                                    59100 Roubaix - France<br><br>

                                    <strong>2. Conditions générales d'utilisation du site et des services proposés</strong><br>
                                    L’utilisation du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> implique l’acceptation pleine et entière des conditions générales d’utilisation décrites ci-après. Ces conditions d’utilisation sont susceptibles d’être modifiées ou complétées à tout moment, les utilisateurs du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> sont donc invités à les consulter de manière régulière.<br><br>

                                    <strong>3. Description des services fournis</strong><br>
                                    Le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> a pour objet de fournir un service de gestion de stock destiné aux petits commerces. Ce service est proposé sous forme d’abonnement payant, et inclut diverses fonctionnalités permettant une gestion optimisée des stocks.<br><br>

                                    <strong>4. Propriété intellectuelle et contrefaçons</strong><br>
                                    Quantisys est propriétaire des droits de propriété intellectuelle ou détient les droits d’usage sur tous les éléments accessibles sur le site, notamment les textes, images, graphismes, logo, icônes, sons, logiciels, etc. Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de Quantisys.<br><br>

                                    <strong>5. Limitations de responsabilité</strong><br>
                                    Quantisys ne pourra être tenue responsable des dommages directs et indirects causés au matériel de l’utilisateur, lors de l’accès au site <a href="https://www.quantisys.fr">www.Quantisys.fr</a>, et résultant soit de l’utilisation d’un matériel ne répondant pas aux spécifications indiquées au point 4, soit de l’apparition d’un bug ou d’une incompatibilité. Quantisys ne pourra également être tenue responsable des dommages indirects (tels par exemple qu’une perte de marché ou perte d’une chance) consécutifs à l’utilisation du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a>.<br><br>

                                    <strong>6. Gestion des données personnelles</strong><br>
                                    Les informations recueillies sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> sont nécessaires pour la gestion de votre abonnement et pour l’établissement des factures. Elles font l’objet d’un traitement informatique destiné à Quantisys. En application de la loi informatique et libertés du 6 janvier 1978 modifiée, vous bénéficiez d’un droit d’accès, de rectification, de suppression et d’opposition aux informations vous concernant, que vous pouvez exercer en vous adressant à <a href="mailto:contact@Quantisys.com">contact@Quantisys.com</a>.<br><br>

                                    <strong>7. Droit applicable et attribution de juridiction</strong><br>
                                    Tout litige en relation avec l’utilisation du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> est soumis au droit français. Il est fait attribution exclusive de juridiction aux tribunaux compétents de Bordeaux.<br><br>

                                    <strong>8. Principales lois concernées</strong><br>
                                    - Loi n° 78-17 du 6 janvier 1978, notamment modifiée par la loi n° 2018-493 du 20 juin 2018 relative à l'informatique, aux fichiers et aux libertés.<br>
                                    - Règlement (UE) 2016/679 du Parlement européen et du Conseil du 27 avril 2016 (RGPD).<br><br>

                                    <strong>9. Lexique</strong><br>
                                    Utilisateur : Internaute se connectant, utilisant le site susnommé.<br>
                                    Informations personnelles : « Les informations qui permettent, sous quelque forme que ce soit, directement ou non, l'identification des personnes physiques auxquelles elles s'appliquent » (article 4 de la loi n° 78-17 du 6 janvier 1978).
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Hero Section End -->

    <!-- ====== Footer Section Start -->
    <footer class="relative z-10 bg-primary">
        <div class="mt-12 bg-[#101541] py-8 lg:mt-[60px]">
            <div class="container mx-auto">
                <div class="-mx-4 flex flex-wrap">
                    <div class="w-full px-4 md:w-1/3 lg:w-1/2">
                        <div class="my-1 flex justify-center md:justify-start">
                            <p class="text-base text-[#efefef]">&copy; Quantisys</p>
                        </div>
                    </div>
                    <div class="w-full px-4 md:w-2/3 lg:w-1/2">
                        <div class="my-1">
                            <div class="-mx-3 flex flex-wrap items-center justify-center md:justify-end">
                                <a
                                    href="/cgv"
                                    class="px-3 text-base text-[#efefef] hover:text-white">
                                    Conditions Générales de Vente
                                </a>
                                <a
                                    href="/mentions-legales"
                                    class="px-3 text-base text-[#efefef] hover:text-white">
                                    Mentions Légales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
a {
    display: inline;
    text-decoration: underline;
    white-space: nowrap;
}

a:hover {
    text-decoration: none;
    color: #1e40af;
}

p {
    text-align: left;
}

strong {
    font-weight: bold;
    text-decoration: underline;
}

h1, h2, h3, h4, h5, h6 {
    text-align: center;
}
</style>
