define(['require', 
    'Backbone', 
    'Utils',
    'Photo', 
    'PhotoItemView', 
    'PhotoItemBase64View', 
    'text!templates/gallery.html', 
    'Mustache', 
    'lang', 
    'unveil', 
    'lightbox'
    ], function (require, 
        Backbone,
        Utils,
        Photo, 
        PhotoItemView, 
        PhotoItemBase64View, 
        templateGallery, 
        Mustache, 
        lang, 
        unveil,
        lightbox) {
        
    var PhotoListView = Backbone.View.extend ({
         template: _.template(templateGallery),
         uploadImages: false,	 
         initialize:function () {
             this.collection.bind("reset", this.render, this);
             this.csrf = utils.getCookie('csrf');
             $('.photo').remove();
         },
         el: $('#photoContainer'),
         render: function (eventName) {
             document.title = 'Backbone Gallery';
             //this.$el.append(Mustache.render(this.template(), {}));
             _.each(this.collection.models, function (photo) {
                this.$el.append(new PhotoItemView({model: photo}).render().el);
             }, this);
             $('img').unveil(200, function() {
                $(this).load(function() {
                });
             });
         },
         events: {
             'click input#addImage': 'authenticate'
         },
         authenticate: function(e) {
            console.log(e);
         },
         openView: function (e) {
             this.close();
             var view = e.currentTarget.id.replace('open', '').toLowerCase();
             Backbone.history.navigate(view, true);
             return false;
         }
    });
    return PhotoListView;
});
