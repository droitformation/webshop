// -------------------------------
// Initialize Data Tables
// -------------------------------

$(document).ready(function() {

    var init = function()
    {
        var api    = this.api();
        var column = api.column(5);

        var select = $('<select class="form-control"><option value="">Filtrer par status</option></select>')
            .appendTo( $(column.footer()).empty()).on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search( val ? '^'+val+'$' : '', true, false ).draw();
        });

        column.data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        });
    };

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

    $('#arrets').DataTable({
        initComplete: init,
        language    : langues,
        pagingType  : 'simple'
    });

    $('.simple').DataTable({
        initComplete: init,
        language    : langues,
        pagingType  : 'simple'
    });

    $('.simple-table').DataTable({
        language: langues,
        pagingType: 'simple',
        "columnDefs": [{
            "targets"  : 'no-sort',
            "orderable": false
        }]
    });

    $('#generic').DataTable({
        language: langues,
        pagingType: 'simple',
        "columnDefs": [{
            "targets"  : 'no-sort',
            "orderable": false
        }]
    });

    $('#abos-table').DataTable({
        initComplete: init,
        language: langues,
        pageLength: 50,
        pagingType: 'simple',
        "columnDefs": [{
            "targets"  : 'no-sort',
            "orderable": false
        }]
    });

    $('.generic').DataTable({
        language: langues,
        pagingType: 'simple',
        "columnDefs": [
            { "visible": false, "targets": 0 }
        ],
        "drawCallback": function ( settings ) {
            var api  = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( group && (last !== group) )
                {
                    $(rows).eq( i ).before(
                        '<tr class="group isGroupeMain"><td>Groupe '+group+'</td><td><a class="btn btn-info btn-xs" href="admin/inscription/groupe/'+group+'">Changer le détenteur</a></td><td></td><td><a class="btn btn-success btn-xs" href="admin/inscription/add/'+group+'">Ajouter un participant</a></td><td colspan="4"></td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    var table = $('.users_table').DataTable({
        "serverSide": true,
        "ajax": {
            "url": "admin/users"
        },
        "columns": [
            {data: 'id', title: 'Editer'},
            {data: 'nom', title : 'Nom'},
            {data: 'email', title: 'E-mail'},
            {data: 'adresse', title: 'Adresse(s)'},
            {data: 'delete'}
        ],
        language: langues
    });

    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Recherche...');
    $('.dataTables_length select').addClass('form-control');

});