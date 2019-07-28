# Changelog 5.2 to 5.3

# Fichiers/Dossiers à supprimer (update)
`/kernel/lib/js/lightcase`  
`/kernel/lib/js/lightcase/lightcase.js`    
`/kernel/lib/js/lightcase/css/lightcase.css`  
`/kernel/lib/js/lightcase/fonts/lightcase.ttf`  
`/kernel/lib/js/lightcase/fonts/lightcase.woff`

# Lightbox
La lightbox a été déplacée dans le thème et profite des icones Font Awesome 5
- déplacement et modification du fichier lightcase.css dans le thème
- transfert des propriétés de couleur dans le fichier colors.css
```
/* -- lightcase.css
    #      #####  #####  #   #  #####  ####   #####  #   #
    #        #    #      #   #    #    #   #  #   #   # #
    #        #    #  ##  #####    #    ####   #   #    #
    #        #    #   #  #   #    #    #   #  #   #   # #
    #####  #####  #####  #   #    #    ####   #####  #   #
----------------------------------------------------------------------------- */
a[class*='lightcase-icon-'], a[class*='lightcase-icon-']:focus {
    color: rgba(255, 255, 255, 0.2);
}

a[class*='lightcase-icon-']:hover {
    color: #FFFFFF;
    text-shadow: 0 0 0.618em #FFFFFF;
}

.lightcase-isMobileDevice a[class*='lightcase-icon-']:hover {
    color: #aaa;
}

#lightcase-case {
    text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

@media screen and (min-width: 641px) {
    html:not([data-lc-type=error]) #lightcase-content {
        background-color: #FFFFFF;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    }
}

@media screen and (min-width: 641px) {
    html[data-lc-type=image] #lightcase-content, html[data-lc-type=video] #lightcase-content {
        background-color: #333333;
    }
}

#lightcase-content h1,
#lightcase-content h2,
#lightcase-content h3,
#lightcase-content h4,
#lightcase-content h5,
#lightcase-content h6,
#lightcase-content p {
    color: #333;
}

#lightcase-case p.lightcase-error {
    color: #CE271A;
}

#lightcase-info #lightcase-title {
    color: #FFFFFF;
}

#lightcase-info #lightcase-caption {
    color: #FFFFFF;
}

#lightcase-info #lightcase-sequenceInfo {
    color: #FFFFFF;
}

#lightcase-loading {
    text-shadow: 0 0 0.618em #FFFFFF;
}

#lightcase-loading, #lightcase-loading:focus {
    color: #FFFFFF;
}

#lightcase-overlay {
    background: #366493;
}
```
- modifier l'appel du fichier lightcase.css dans templates/{THEME}/frame.tpl  
avec le cache
`/templates/{THEME}/theme/lightcase.css;`  
sans le cache
`<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/lightcase.css" type="text/css" media="screen" />`

# Tableaux
Les tableaux en bbcode sont maintenant responsifs
- ajouter la classe .table dans toutes les tables en __html__ ou la classe .table-no-header pour les tables sans entête (ex: galerie)
- mise à jour du fichier table.css  

Remplacer
```
table.formatter-table th.formatter-table-head {...
table.formatter-table th.formatter-table-head p {...
```  

par
```
table.formatter-table td.formatter-table-head {...
table.formatter-table td.formatter-table-head p {...
```
ajouter
```
table.bt.formatter-table td.formatter-table-head {
	display: none;
}

table.bt tfoot th,
table.bt tfoot td,
table.bt tbody td {
	display: flex;
	display: -ms-flexbox;
	display: -webkit-flex;
	vertical-align: top;
}

table.bt tfoot th::before,
table.bt tfoot td::before,
table.bt tbody td::before {
  content: attr(data-th) ": ";
  display: inline-block;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  font-weight: bold;
  width: 6.5em;
}

table.bt tfoot th.bt-hide,
table.bt tfoot td.bt-hide,
table.bt tbody td.bt-hide {
  display: none;
}

table.bt tfoot th .bt-content,
table.bt tfoot td .bt-content,
table.bt tbody td .bt-content {
  vertical-align: top;
}

table.bt.bt--no-header tfoot td::before,
table.bt.bt--no-header tbody td::before {
  display: none;
}
```
- Des classes ont été rajoutées pour être utilisées dans le BBCode  

quand la table comporte trop de colonnes   
`[container class="responsive-table"][table]....[/table][/container]`
```
.responsive-table {
	max-width: 100%;
	overflow: auto;
}
```
Si voulez des bordures sur toutes les cellules     
`[container class="bordered"][table]....[/table][/container]`
```
.bordered td {
	border-width: 1px 0 0 1px;
	border-style: solid;
	border-color: transparent;
}
.bordered td:last-child {
	border-width: 1px 1px 0 1px;
}
.bordered tr:last-child td {
	border-width: 1px 0 1px 1px;
}
.bordered tr:last-child td:last-child {
	border-width: 1px;
}
```

# Plugins jQuery
Afin d'améliorer la maintenance des plugins jquery, certains ont été sorti du fichier global.js et déplacés dans le dossier template  
- **_SI_** le js_bottom.tpl est dans le thème
    - supprimer l'appel du lightcase.js
    - remplacer le script du plugin basictable:     
```
// BBCode table with no header
jQuery('.formatter-table').each(function(){
    $this = jQuery(this).find('tbody tr:first-child td');
    if ($this.hasClass('formatter-table-head')) {}
    else
        $this.closest('.formatter-table').removeClass('table').addClass('table-no-header');
});

// All tables
jQuery('.table').basictable();     
jQuery('.table-no-header').basictable({     
    header: false       
});
```
à noter que l'ancien appel sur les identifiants `jQuery('#table').basictable()` fonctionne toujours si vous ne voulez pas modifier toutes les tables.

- **_SI_** le js_top.tpl est dans le thème, ajouter les appel des plugins après l'appel du global.js
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/autocomplete.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/basictable.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/lightcase.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/sortable.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/menumaker.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/tooltip.js"></script>`

# Tooltip
Afin de supprimer les title="" qui limitent les performences en mobile, un système de tooltip est mis en place sur les aria-label, ce qui permet l'affichage des textes cachés, au survol de la souris en profitant des attributs d'accessibilité  
- ajouter les classes du tooltip
colors.css:  
```
#tooltip {
	box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.15);
	background-color: #FFFFFF;
}
```
content.css:
```
#tooltip {
    position: absolute;
    padding: 0.228em 0.456em;
    font-size: 0.809em;
    display: inline-block;
    opacity: 0;
    max-width: 200px;
    width: auto;
    z-index: 1000;
}

#tooltip.position-t{ margin-top:  -9px; }
#tooltip.position-b{ margin-top:   9px; }
#tooltip.position-r{ margin-left:  9px; }
#tooltip.position-l{ margin-left: -9px; }

#tooltip.position-tr{ margin-left:  7px; margin-top: -7px; }
#tooltip.position-br{ margin-left:  7px; margin-top:  7px; }
#tooltip.position-bl{ margin-left: -7px; margin-top:  7px; }
#tooltip.position-tl{ margin-left: -7px; margin-top: -7px; }
```
- déclarez un aria-label ou vous voulez voir apparaitre un tooltip  
`<button aria-label="Fermer">X</button>`