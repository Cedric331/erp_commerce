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
    <Head title="Conditions Générales de Vente" />
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
            <div class="-mx-4">
                <div class="w-full flex justify-center">
                    <div class="hero-content text-center">
                        <h1 class="mb-10 text-4xl font-bold leading-snug text-dark sm:text-[42px] lg:text-[40px] xl:text-[42px]">
                            Conditions Générales de Vente (CGV)
                        </h1>
                        <div class="w-full px-4">
                            <div class="hero-content text-center flex justify-center">
                                <p class="mb-8 max-w-[680px] text-base text-body-color">
                                    <strong>1. Préambule</strong><br>
                                    Les présentes conditions générales de vente (CGV) régissent les relations contractuelles entre la société Quantisys (ci-après désignée "nous" ou "Quantisys") et toute personne physique ou morale souhaitant souscrire à un abonnement pour les services proposés sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> (ci-après désignée "vous" ou "le Client").<br><br>

                                    <strong>2. Objet</strong><br>
                                    Les présentes CGV ont pour objet de définir les conditions dans lesquelles Quantisys propose ses services de gestion de stock en ligne aux petits commerces. Le service est accessible via abonnement payant.<br><br>

                                    <strong>3. Acceptation des conditions</strong><br>
                                    En souscrivant à un abonnement sur notre site <a class="inline-block" href="https://www.quantisys.fr">www.Quantisys.fr</a>, vous acceptez sans réserve les présentes CGV. Quantisys se réserve le droit de modifier les CGV à tout moment. Les CGV applicables sont celles en vigueur à la date de souscription de l’abonnement.<br><br>

                                    <strong>4. Services proposés</strong><br>
                                    Quantisys propose des services de gestion de stock pour les petits commerces via une plateforme en ligne. Ces services comprennent, entre autres, le suivi des stocks.<br><br>

                                    <strong>5. Abonnement et tarifs</strong><br>
                                    Les services sont proposés sous forme d’abonnement mensuel. Les tarifs en vigueur sont indiqués sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a>. Quantisys se réserve le droit de modifier ses tarifs à tout moment, mais les modifications de tarifs ne s'appliqueront pas aux abonnements en cours jusqu'à leur renouvellement.<br><br>

                                    <strong>6. Modalités de paiement</strong><br>
                                    Le paiement de l’abonnement se fait par carte bancaire ou tout autre moyen de paiement proposé sur le site. Le paiement est sécurisé par notre prestataire de services de paiement. En cas de défaut de paiement, Quantisys se réserve le droit de suspendre ou de résilier l’accès aux services.<br><br>

                                    <strong>7. Droit de rétractation</strong><br>
                                    Conformément à l'article L221-18 du Code de la consommation, vous disposez d'un délai de 14 jours à compter de la souscription de votre abonnement pour exercer votre droit de rétractation sans avoir à justifier de motifs ni à payer de pénalités. Toutefois, ce droit ne s'applique pas si vous avez commencé à utiliser les services avant la fin du délai de rétractation.<br><br>

                                    <strong>8. Résiliation</strong><br>
                                    Vous pouvez résilier votre abonnement à tout moment depuis l'espace de gestion du commerce sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a>. La résiliation prendra effet à la fin de la période d’abonnement en cours. En cas de résiliation pour non-respect des CGV, aucun remboursement ne sera effectué.<br><br>

                                    <strong>9. Responsabilité</strong><br>
                                    Quantisys met tout en œuvre pour assurer la disponibilité et la sécurité de ses services. Cependant, Quantisys ne saurait être tenue responsable des interruptions temporaires des services ou des dommages indirects résultant de l’utilisation des services. La responsabilité de Quantisys est limitée au montant de l’abonnement payé par le Client.<br><br>

                                    <strong>10. Données personnelles</strong><br>
                                    Quantisys s’engage à protéger les données personnelles de ses clients conformément à la législation en vigueur. Les données collectées sont utilisées exclusivement pour la gestion des abonnements et ne sont en aucun cas partagées avec des tiers, sauf obligation légale.<br><br>

                                    <strong>11. Propriété intellectuelle</strong><br>
                                    Tous les éléments du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> sont et restent la propriété intellectuelle exclusive de Quantisys. Toute reproduction, exploitation, rediffusion ou utilisation des éléments du site, qu’ils soient logiciels, visuels ou sonores, est strictement interdite sans l’accord préalable de Quantisys.<br><br>

                                    <strong>12. Droit applicable et juridiction compétente</strong><br>
                                    Les présentes CGV sont soumises au droit français. En cas de litige, les parties s’engagent à rechercher une solution amiable avant de saisir les tribunaux compétents de Bordeaux.<br><br>

                                    <strong>13. Contact</strong><br>
                                    Pour toute question ou réclamation, vous pouvez nous contacter par email à l'adresse suivante : <a href="mailto:contact@quantisys.fr">contact@quantisys.fr</a>.
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
