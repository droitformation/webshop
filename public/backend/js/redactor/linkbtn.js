(function($R)
{
    $R.add('plugin', 'linkbtn', {
        modals: {
            'linkbtn': '<form action="">'
            + '<div class="form-item">'
            + '<label>## linkbtn-label ##</label>'
            + '<input name="btntitle">'
            + '</div>'
            + '<div class="form-item">'
            + '<label>## linkbtn-href ##</label>'
            + '<input name="btnlink">'
            + '</div>'
            + '<div class="form-item">'
            + '<label>## linkbtn-class ##</label>'
            + '<select name="btnclass">'
            + '<option value="btn-primary">Bleu foncé</option><option value="btn-info">Bleu clair</option><option value="btn-success">Vert</option>'
            + '<option value="btn-danger">Rouge</option><option value="btn-warning">Orange</option><option value="btn-default">Gris</option>'
            + '</select>'
            + '</div>'
            + '</form>'
        },
        translations: {
            en: {
                "linkbtn": "Ajouter bouton avec lien",
                "linkbtn-label": "Titre du bouton",
                "linkbtn-href": "Lien sur le bouton",
                "linkbtn-class": "Classe css sur le bouton",
            },
            fr: {
                "linkbtn": "Ajouter bouton avec lien",
                "linkbtn-label": "Titre du bouton",
                "linkbtn-href": "Lien sur le bouton",
                "linkbtn-class": "Classe css sur le bouton",
            }
        },
        init: function(app)
        {
            // define app
            this.app = app;

            // define services
            this.lang = app.lang;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
        },

        // messages
        onmodal: {
            linkbtn: {
                opened: function($modal, $form)
                {
                    $form.getField('btntitle').focus();
                    $form.getField('btnlink').focus();
                    $form.getField('btnclass').focus();
                },
                insert: function($modal, $form)
                {
                    var data = $form.getData();
                    this._insert(data);
                }
            }
        },

        // public
        start: function()
        {
            // create the button data
            var buttonData = {
                title: this.lang.get('linkbtn'),
                api: 'plugin.linkbtn.open',
                icon: '<i class="fa fa-circle-o"></i>',
            };

            // create the button
            var $button = this.toolbar.addButton('linkbtn', buttonData);
        },
        open: function()
        {
            var options = {
                title: this.lang.get('linkbtn'),
                width: '600px',
                name: 'linkbtn',
                handle: 'insert',
                commands: {
                    insert: { title: this.lang.get('insert') },
                    cancel: { title: this.lang.get('cancel') }
                }
            };

            this.app.api('module.modal.build', options);
        },

        // private
        _insert: function(data)
        {
            this.app.api('module.modal.close');

            if (data.btntitle.trim() === '') return;
            if (data.btnclass.trim() === '') return;
            if (data.btnlink.trim() === '') return;

            //var regex = '^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$';
            var link = data.btnlink.trim();

            var button  = '<a class="btn ' + data.btnclass + '" href="'+ link +'" target="_blank">'+ data.btntitle +'</a>';

            this.insertion.insertHtml(button);
        }
    });
})(Redactor);