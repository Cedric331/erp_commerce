<?php

return [

    'label' => 'Exporter :label',

    'modal' => [

        'heading' => 'Exporter :label',

        'form' => [

            'columns' => [

                'label' => 'Champs',

                'form' => [

                    'is_enabled' => [
                        'label' => ':column désactivé',
                    ],

                    'label' => [
                        'label' => 'Nom :column',
                    ],

                ],

            ],

        ],

        'actions' => [

            'export' => [
                'label' => 'Exporter',
            ],

        ],

    ],

    'notifications' => [

        'completed' => [

            'title' => 'Export terminé',

            'actions' => [

                'download_csv' => [
                    'label' => 'Télécharger au format csv',
                ],

                'download_xlsx' => [
                    'label' => 'Télécharger au format xlsx',
                ],

            ],

        ],

        'max_rows' => [
            'title' => 'Fichier trop volumineux',
            'body' => 'Le fichier que vous essayez d\'exporter est trop volumineux. Veuillez réduire la taille du fichier et réessayer.',
        ],

        'started' => [
            'title' => 'Export en cours',
            'body' => 'Votre export a commencé.|Votre export a commencé et :count lignes sera traité en arrière-plan.',
        ],

    ],

    'file_name' => 'export-:export_id-:model',

];
