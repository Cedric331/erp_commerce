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
                        <div class="my-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <strong class="text-yellow-800">Avis important : Site en développement</strong>
                            </div>
                            <p class="text-yellow-700">
                                Quantisys est actuellement en phase de développement actif. Des bugs ou dysfonctionnements peuvent survenir pendant votre utilisation. Nous travaillons constamment à améliorer l'application et apprécions vos retours pour nous aider à l'améliorer. En acceptant ces CGV, vous reconnaissez être informé de l'état de développement du service et acceptez les éventuels désagréments qui pourraient en résulter.
                            </p>
                        </div><br>
                        <h1 class="mb-10 text-4xl font-bold leading-snug text-dark sm:text-[42px] lg:text-[40px] xl:text-[42px]">
                            Conditions Générales de Vente (CGV)
                        </h1>
                        <div class="w-full px-4">
                            <div class="hero-content text-center flex justify-center">
                                <p class="mb-8 max-w-[680px] text-base text-body-color">
                                    <strong>1. Préambule</strong><br>
                                    Les présentes conditions générales de vente (CGV) régissent les relations contractuelles entre la société Quantisys (ci-après désignée "nous" ou "Quantisys") et toute personne physique ou morale souhaitant souscrire à un abonnement pour les services proposés sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> (ci-après désignée "vous" ou "le Client").<br><br>

                                    <strong>2. Objet</strong><br>
                                    Les présentes CGV ont pour objet de définir les conditions dans lesquelles Quantisys propose ses services de gestion de stock en ligne aux petits commerces. Le service est accessible via abonnement payant, après une période d’essai gratuite de 30 jours offerte par commerce.<br><br>

                                    <strong>3. Acceptation des conditions</strong><br>
                                    En souscrivant à un abonnement sur notre site <a class="inline-block" href="https://www.quantisys.fr">www.Quantisys.fr</a>, vous acceptez sans réserve les présentes CGV. Quantisys se réserve le droit de modifier les CGV à tout moment. Les CGV applicables sont celles en vigueur à la date de souscription de l’abonnement.<br><br>

                                    <strong>4. Services proposés</strong><br>
                                    Quantisys propose des services de gestion de stock pour les petits commerces via une plateforme en ligne. Ces services comprennent, entre autres, le suivi des stocks et la gestion des mouvements d’inventaire.<br><br>

                                    <strong>5. Abonnement et tarifs</strong><br>
                                    Chaque commerce bénéficie d’une période d’essai gratuite de 30 jours, sans engagement. Au terme de cette période, l’abonnement mensuel devient payant. Les tarifs en vigueur sont indiqués sur le site <a href="https://www.quantisys.fr">www.Quantisys.fr</a>. Quantisys se réserve le droit de modifier ses tarifs à tout moment. Les modifications de tarifs n’affecteront pas les abonnements en cours jusqu'à leur renouvellement.<br><br>

                                    <strong>6. Modalités de paiement</strong><br>
                                    Le règlement de l’abonnement s’effectue exclusivement via Stripe, notre prestataire de paiement sécurisé. Aucune donnée bancaire n’est stockée sur notre plateforme. En cas de défaut de paiement, Quantisys se réserve le droit de suspendre ou de résilier l’accès aux services.<br><br>

                                    <strong>7. Droit de rétractation</strong><br>
                                    Conformément à l'article L221-18 du Code de la consommation, vous disposez d'un délai de 14 jours à compter de la souscription de votre abonnement payant pour exercer votre droit de rétractation. Cependant, ce droit ne s'applique pas si vous avez commencé à utiliser les services avant la fin du délai de rétractation.<br><br>

                                    <strong>8. Remboursement</strong><br>
                                    Nous attirons votre attention sur le fait qu’aucun remboursement n’est possible, y compris en cas de résiliation anticipée de l’abonnement ou d'interruption de l'utilisation de nos services. Toutefois, vous conservez l'accès aux fonctionnalités jusqu’à la fin de la période d’abonnement en cours.<br><br>

                                    <strong>9. Résiliation</strong><br>
                                    Vous pouvez résilier votre abonnement à tout moment via votre espace personnel sur le site. La résiliation prendra effet à la fin de la période d’abonnement en cours. En cas de non-respect des présentes CGV, Quantisys se réserve le droit de suspendre ou résilier l'accès sans remboursement.<br><br>

                                    <strong>10. Responsabilité</strong><br>
                                    Quantisys accorde une grande importance à la fiabilité et à la sécurité de ses services. Nous mettons tout en œuvre pour garantir un fonctionnement optimal de la plateforme. Toutefois, des interruptions temporaires ou anomalies peuvent exceptionnellement survenir. Notre responsabilité est limitée au montant de l’abonnement payé par le Client pour la période en cours.<br><br>

                                    <strong>11. Données personnelles</strong><br>
                                    Quantisys s'engage à respecter la confidentialité des données personnelles et à les traiter conformément à la réglementation en vigueur. Les données sont utilisées exclusivement pour la gestion des abonnements et ne sont pas communiquées à des tiers sans votre consentement, sauf obligation légale.<br><br>

                                    <strong>12. Propriété intellectuelle</strong><br>
                                    L'ensemble des éléments du site <a href="https://www.quantisys.fr">www.Quantisys.fr</a> est la propriété exclusive de Quantisys. Toute reproduction ou utilisation, partielle ou totale, est interdite sans autorisation écrite préalable.<br><br>

                                    <strong>13. Droit applicable et juridiction compétente</strong><br>
                                    Les présentes CGV sont soumises au droit français. En cas de litige, les parties rechercheront en priorité une solution amiable. À défaut, les tribunaux de Bordeaux seront seuls compétents.<br><br>

                                    <strong>14. Contact</strong><br>
                                    Pour toute question ou demande d’assistance, vous pouvez nous contacter à l’adresse suivante : <a href="mailto:contact@quantisys.fr">contact@quantisys.fr</a>.
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
