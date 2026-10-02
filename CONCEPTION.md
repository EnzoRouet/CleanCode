# Note de conception

## 1. Choix principaux

Premier choix, nous avons décider d'appliquer le SRP sur le fichier BookingService.php. Cela a donc consister au fait de découper les responsabilité de cette classe qui était énorme avec énormément de rôles comme le calcul du prix total, les notifications, paiements. On a donc décider de découper en plusieurs classes spécialisées dans un rôle chacune.

Second choix, nous avons décider de faire en sorte de réduire le couplage un maximum en instanciant les classes au dehors des classes puis en les injectant dans les paramètres pour augmenté la testabilité ainsi que le controle depuis index.php

## 2. Principes SOLID mobilisés

S - SRP

Problème initial : Le BookingService gérait les appels aux autres classes, le calcul des réduction, l'envois de message etc
Classes concernées : PricingCalculator, EmailService, BookingService
Bénéfice obtenu : Le code est plus lisible et testable, une modification des règles de calcul des pass 3 jours n'impactera plus jamais le service de réservation central

O - Open/Closed

Problème initial : L'ajout de la fonctionnalité Analytics ou SMS nécessitait de modifier le code de la méthode confirm(), au risque de casser la logique existante
Classes concernées : BookingService, BookingObserver, AnalyticsConfirmationObserver
Bénéfice obtenu : Le système est ouvert à l'extension mais fermé à la modification

D - DIP

Problème initial : Le service était couplé a l'instanciation de classes
Classes concernées : BookingService, PaymentGateway, StripeAdapter.
Bénéfice obtenu : BookingService dépend désormais d'une abstraction, on peut remplacer Stripe par PayFast instantanément sans modifier le service métier

## 3. Design Patterns éventuellement utilisés

Pour le ticket numéro 102 j'ai anticipé l'utilisation du pattern Strategy. J'ai décider de l'utiliser car dans l'énoncer on nous dit que le service commercial pourrait rajouter de nouevelles politiques tarifaires. Dans l'immédiat une simple méthode aurait suffit mais la création de l'interface PricingStrategy garantit le respect de l'OCP. Ainsi on pourra aisément rajouter de nouvelles règles sans casser l'existent

Pour le ticket numéro 103, j'ai utilisé le pattern Adapter afin de rendre le SDK externe PayFastSdk compatible avec l'interface commune PaymentGateway. Cela permet d'isoler les détails techniques de l'API externe et d'éviter que le code métier (BookingService) ne dépende directement de son implémentation

Pour le ticket numéro 104, j'ai utilisé le pattern Observer. J'ai décider de l'utiliser car cela nous permet de pouvoir rajouter des reactions quand on veut de manière aisée sans cassé ce qui fonctionne déjà

Pour le ticket numéro 105 , J'ai utilisé le pattern Decorator pour "envelopper" le système de paiement. Cela permet d'ajouter ces actions techniques sans modifier le code d'origine de Stripe ou PayFast, et sans polluer la logique métier

## 4. Solutions envisagées puis écartées

Passage de chaînes de caractères pour piloter la logique : L'idée de passer 'stripe' ou 'payfast' en paramètre au service a été écartée au profit de l'injection d'objets, cela permet à PHP de vérifier les contrats stricts et d'éviter les erreurs sans qu'on s'en appercoive

Condition énorme pour les prix avec des if/elseif : Écartée au profit d'une classe dédiée, afin d'éviter une indentation énorme de if/else et une complexité cognitive trop grande en ayant a devoir se souvenir du contexte des 3 ou 4 if/elseif avant

## 5. Ce que nous améliorerions avec plus de temps

Avec le temps on pourrait rajouter une classe pour modéliser la DB afin de seulement faire appel a cette classe pour save a la fin de BookingService pour finir notre SRP

On pourrait aussi faire un observer qui déclencherai la confirmation de la réservation afin décharger la classe BookingService et potentiellement changer l'id du stripe qui est actuellement en référence au prix payer pour la réservation mais on pourrait donc avec les mêmes id si deux clients payent la même somme
