## Issues avec l'object-mapper

### Pas d'auto mapping si des attributs map sont définis sur au moins une prop

Si je ne définis pas les attributs Map sur Store.id et Store.title, ils ne sont pas mappés => l'auto mapping ne fonctionne pas

Ce que j'observe : si je supprime l'attribut Map de Store.title, ce champs n'apparait plus dans la réponse API.

Ce que j'attends : l'auto mapping fonctionne, et le champs title est mappé même sans l'attribut Map, parce que l'object mapper
repère un champs du même nom dans la source.

> Note 1 : probable rapport avec https://github.com/symfony/symfony/issues/63641#issuecomment-4252685456

> Note 2 : on passe toujours par api/src/ObjectMapper/ReverseClassObjectMapperMetadataFactory.php::63, ce qui fait que le fix
dans la boucle en dessous ne fonctionne pas.



### La création d'une Api Resource perturbe le fonctionnement du mapping des embedded mappés depuis la même entité

Dans ma configuration actuelle, la ressource Store définit un champs 'manager' qui est mappé pour fournir l'embedded
Api/Embedded/Store/Manager. ça fonctionne bien, cependant, si je définis également une ressource Manager mappée sur la 
même entité Manager, cette api resource est prise en compte prioritairement par l'object mapper.

Ce que j'observe : si j'ajoute l'api resource Manager (exécuter depuis la racine du 
projet : `mv api/src/Api/Resource/Manager.php.off api/src/Api/Resource/Manager.php`), j'ai l'erreur suivante : 
`Expected argument of type \"?App\\Api\\Embedded\\Store\\Manager\", \"App\\Api\\Resource\\Manager\" given at property path \"manager\".`.

Ce que j'attends : dans ce cas précis, que l'object mapper ne prenne pas en compte l'api resource Manager, mais uniquement l'embedded Manager.




