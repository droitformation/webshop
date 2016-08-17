
!function ($) {
    $(function(){

        var init = function() {

            var api    = this.api();
            var column = api.column(3);
            var select = $('<select class="form-control input-sm"><option value="">Filtrer par</option></select>')
                .appendTo( $(column.footer()).empty() )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );
                    column.search( val ? '^'+val+'$' : '', true, false ).draw();
                } );

            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            });
        };

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "num-no-pre": function ( a ) {
                var no = String(a).replace( /<[\s\S]*?>/g, "" );
                var res = no.split('/');
                console.log(res[1]);
                return parseFloat( res[1] );
            },
            "num-no-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },
            "num-no-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        } );

        var langues = {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:     "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de _START_ &agrave; _END_ sur _TOTAL_ lignes",
            infoEmpty:      "Affichage de 0 &agrave; 0 sur 0 lignes",
            infoFiltered:   "(filtr&eacute; de _MAX_ lignes au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        };

        $('.table-list').DataTable({
            columnDefs: [
                { type: 'num-no', targets: 2 },{ "targets":'no-sort', "orderable": false}
            ],
            pageLength: -1,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
            language: langues,
            pagingType: 'simple'
        });

        $('.dataTables_filter input').addClass('form-control').attr('placeholder','Recherche...');
        $('.dataTables_length select').addClass('form-control');

    });
}(window.jQuery);