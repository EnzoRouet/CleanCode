# Audit initial

## 1. Comportement observable

L'application permet de calculer le montant d'une réservation en fonction du statut de la personne pour lui appliquer des réductions ou non
L'application permet de pouvoir payer avec stripe qui est un prestataire externe
L'application enregistre la réservation en base de donnée en tant que confirmée
L'application envoie un mail de confirmation a la personne qui a réservée

## 2. Problèmes identifiés

| #   | Problème                                            | Catégorie                               | Impact    |
| --- | --------------------------------------------------- | --------------------------------------- | --------- |
| 1   | Vérification format email dans le service           | Primitive obsession                     | MOYEN     |
| 2   | Vérification au cas par cas des méthode de paiement | Couplage/Responsabilité/maintenabilité  | IMPORTANT |
| 3   | Nombres magiques                                    | Lisibilité/Maintenabilité               | MOYEN     |
| 4   | Le service instancie directement les classes        | Testabilité                             | MOYEN     |
| 5   | Le service calcul le total lui même                 | règles métier/Maintenabilité/Lisibilité | CRITIQUE  |
| 6   | Le service enregistre directement en base de donnée | SRP/testabilité                         | IMPORTANT |

## 3. Nos trois priorités

1. La vérification des méthodes de paiement car c'est ce qui peut le plus alourdir le code si on a plusieurs prestataires
2. Le calcul du total avec les réductions comprises car les règles de calcul sont souvent amenées a changer donc il faut un endroit dédier pour facilité les modifications et ajouts
3. L'enregistrement base de données directement dans le service car le couplage est trop fort et pas maintenable

## 4. Risques avant refactoring

Régression sur la facturation : En extrayant la logique de calcul du prix hors de BookingService, on risque de casser la facturation au client

Régression sur les statuts : Oublier de passer le statut de la commande à "confirmed" après refactorer le code

Casser le flux critique : Le paiement Stripe et l'insertion SQL étant fortement couplés au service, les isoler risque de casser l'étape finale d'achat si on réinjecte mal les dépendances

## Justification Pattern

Pour le ticket numéro 102 j'ai anticipé l'utilisation du pattern Strategy. J'ai décider de l'utiliser car dans l'énoncer on nous dit que le service commercial pourrait rajouter de nouevelles politiques tarifaires. Dans l'immédiat une simple méthode aurait suffit mais la création de l'interface PricingStrategy garantit le respect de l'OCP. Ainsi on pourra aisément rajouter de nouvelles règles sans casser l'existent

Pour le ticket numéro 103, j'ai utilisé le pattern Adapter afin de rendre le SDK externe PayFastSdk compatible avec l'interface commune PaymentGateway. Cela permet d'isoler les détails techniques de l'API externe et d'éviter que le code métier (BookingService) ne dépende directement de son implémentation.

Pour le ticket numéro 105 , J'ai utilisé le pattern Decorator pour "envelopper" le système de paiement. Cela permet d'ajouter ces actions techniques sans modifier le code d'origine de Stripe ou PayFast, et sans polluer la logique métier.