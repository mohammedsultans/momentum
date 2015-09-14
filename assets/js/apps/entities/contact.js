define(["app", "config/storage/localstorage"], function(TheMarket){
  TheMarket.module('Entities', function(Entities, TheMarket, Backbone, Marionette, $, _){
    Entities.Contact = Backbone.Model.extend({
      urlRoot: "contacts",

      defaults: {
        firstName: '',
        lastName: '',
        phoneNumber: ''
      },

      validate: function(attrs, options) {
        var errors = {}
        if (! attrs.firstName) {
          errors.firstName = "can't be blank";
        }
        if (! attrs.lastName) {
          errors.lastName = "can't be blank";
        }
        else{
          if (attrs.lastName.length < 2) {
            errors.lastName = "is too short";
          }
        }
        if( ! _.isEmpty(errors)){
          return errors;
        }
      }
    });

    Entities.configureStorage(Entities.Contact);

    Entities.ContactCollection = Backbone.Collection.extend({
      url: "contacts",
      model: Entities.Contact,
      comparator: "firstName"
    });

    Entities.configureStorage(Entities.ContactCollection);

    var initializeContacts = function(){
      var contacts = new Entities.ContactCollection([
        { id: 1, firstName: 'Alice', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 2, firstName: 'Bob', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 3, firstName: 'Charlie', lastName: 'Campbell', phoneNumber: '555-0129' },
        { id: 4, firstName: 'Alicee', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 5, firstName: 'Bobe', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 6, firstName: 'Charelie', lastName: 'Campbell', phoneNumber: '555-0129' },
        { id: 7, firstName: 'Aliece', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 8, firstName: 'Beob', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 9, firstName: 'Chearlie', lastName: 'Campbell', phoneNumber: '555-0129' },
        { id: 10, firstName: 'Aleice', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 11, firstName: 'Boeb', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 12, firstName: 'Ceharlie', lastName: 'Campbell', phoneNumber: '555-0129' },
        { id: 13, firstName: 'Aelice', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 14, firstName: 'Bfob', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 15, firstName: 'Cfharlie', lastName: 'Campbell', phoneNumber: '555-0129' },
        { id: 16, firstName: 'Aflice', lastName: 'Arten', phoneNumber: '555-0184' },
        { id: 17, firstName: 'Bfob', lastName: 'Brigham', phoneNumber: '555-0163' },
        { id: 18, firstName: 'Chfarlie', lastName: 'Campbell', phoneNumber: '555-0129' }
      ]);
      contacts.forEach(function(contact){
        contact.save();
      });
      return contacts.models;
    };

    Entities.Journal = Backbone.Model.extend({

      urlRoot: "",

      defaults: {
        name: '',
        coverUrl: ''
      }
    });

    Entities.JournalCollection = Backbone.Collection.extend({
      url: "presentation/journals",
      model: Entities.Journal,
    });

    Entities.configureStorage(Entities.Journal);

    Entities.configureStorage(Entities.JournalCollection);

    var initializeJournals = function(){
      var journals = new Entities.JournalCollection([
        {"name":"American Economic Review","cover_img_url":"./assets/journalcovers/american econ review.jpg"},
        {"name":"American Journal of Agricultural Economics","cover_img_url":"./assets/journalcovers/American Journal of Agricultural Econ.jpg"},
        {"name":"Applied Economics","cover_img_url":"./assets/journalcovers/applied economics.jpg"},
        {"name":"Ecological Economics","cover_img_url":"./assets/journalcovers/ecological econo.jpg"},
        {"name":"Economic Journal","cover_img_url":"./assets/journalcovers/econ journal.jpg"},
        {"name":"Economic Letters","cover_img_url":"./assets/journalcovers/econ letters.jpg"},
        {"name":"Energy Economics","cover_img_url":"./assets/journalcovers/energy econ.jpg"},
        {"name":"European Economic Review","cover_img_url":"./assets/journalcovers/european econ review.jpg"},
        {"name":"Games & Economic Behavior","cover_img_url":"./assets/journalcovers/games and econ behaviour.jpg"},
        {"name":"Health Economics","cover_img_url":"./assets/journalcovers/health econ.jpg"},
        {"name":"Journal of Accounting & Economics","cover_img_url":"./assets/journalcovers/acc and econ.jpg"}
      ]);
      journals.forEach(function(journal){
        journal.save();
      });
      return journals.models;
    };


    var API = {
      getContactEntities: function(){
        var contacts = new Entities.ContactCollection();
        var defer = $.Deferred();
        contacts.fetch({
          success: function(data){
            defer.resolve(data);
          }
        });
        var promise = defer.promise();
        $.when(promise).done(function(contacts){
          if(contacts.length === 0){
            // if we don't have any contacts yet, create some for convenience
            var models = initializeContacts();
            contacts.reset(models);
          }
        });
        return promise;
      },

      getContactEntity: function(contactId){
        var contact = new Entities.Contact({id: contactId});
        var defer = $.Deferred();
        setTimeout(function(){
          contact.fetch({
            success: function(data){
              defer.resolve(data);
            },
            error: function(data){
              defer.resolve(undefined);
            }
          });
        }, 2000);
        return defer.promise();
      },

      getJournalEntities: function(){
        var journals = new Entities.JournalCollection();
        var defer = $.Deferred();
        journals.fetch({
          success: function(data){
            defer.resolve(data);
          }
        });
        var promise = defer.promise();
        $.when(promise).done(function(journals){
          if(journals.length === 0){
            // if we don't have any journals yet, create some for convenience
            var models = initializeJournals();
            journals.reset(models);
          }
        });
        return promise;
      },

      getJournalEntity: function(journalId){
        var journal = new Entities.journal({id: journalId});
        var defer = $.Deferred();
        setTimeout(function(){
          journal.fetch({
            success: function(data){
              defer.resolve(data);
            },
            error: function(data){
              defer.resolve(undefined);
            }
          });
        }, 2000);
        return defer.promise();
      }
    };

    TheMarket.reqres.setHandler("journal:entities", function(){
      return API.getJournalEntities();
    });

    TheMarket.reqres.setHandler("contact:entities", function(){
      return API.getContactEntities();
    });

    TheMarket.reqres.setHandler("contact:entity", function(id){
      return API.getContactEntity(id);
    });

    TheMarket.reqres.setHandler("contact:entity:new", function(id){
      return new Entities.Contact();
    });
  });

  return ;
});