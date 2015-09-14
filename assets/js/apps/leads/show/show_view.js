define(["app", "tpl!apps/templates/leads.tpl", "tpl!apps/templates/addlead.tpl", "tpl!apps/templates/addcontactlead.tpl", "tpl!apps/templates/searchhead.tpl", "tpl!apps/templates/contact.tpl", "tpl!apps/templates/contacts.tpl", "tpl!apps/templates/customer.tpl", "backbone.syphon"], 
	function(System, leadsTpl, addleadTpl, addcontactleadTpl, searchheadTpl, contactTpl, contactsTpl, customerTpl){
  System.module('LeadsApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Leadss = Marionette.CompositeView.extend({

      className: "col-md-12",

      template: leadsTpl,

      itemView: View.Contact,

      itemViewContainer: "ul.basic-list"
    });

    View.Leads = Marionette.ItemView.extend({      

        template: leadsTpl,

        events: {
          "click .ioptions #btn-edit": "editIssue",
        },

        onShow: function(){
          $("#leadscont").unwrap();
          
          var ul = $('.basic-list');
          ul.empty();

          $.get("http://192.168.43.29/chases/leads.php?all", function(result) {
              var res = JSON.parse(result);
              res.forEach(function(element, index){
                var tpl = $('<li><img src="img/person.png" alt="img" class="img"><b>'+element['name']+'</b><span class="desc">'+element['phone']+'</span></li>');
                tpl.appendTo(ul);
              });
          });
        },

        editIssue: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          this.trigger("edit", this.model);
          //alert("Head to latest article");
          //this.trigger("edit:division", this);
        }
    });

    View.AddLead = Marionette.ItemView.extend({      

        template: addleadTpl,

        events: {
          "click .btnsub": "addLead",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.selectpicker').selectpicker();
        },

        addLead: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          this.trigger("create", data);
        }
    });

    View.Client = Marionette.ItemView.extend({      

        template: customerTpl,

        events: {
          "click .nsave": "addCustomer",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          require.undef('plugins');
          require.undef('sweetalert');
          require(["sweetalert", "sweetalert"], function(){
              $('.selectpicker').selectpicker();
          });
          
        },

        addCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          swal("Success!", "The record has been created.", "success");
          //this.trigger("create", data);
        },

        onSuccess: function(e) { 
          swal("Success!", "The record has been created.", "success");
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }
    });

    View.EditCustomer = Marionette.ItemView.extend({      

        template: customerTpl,

        events: {
          "click .btnsub": "addCustomer",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.selectpicker').selectpicker();
        },

        addCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          this.trigger("create", data);
        }
    });

    View.AddContactLead = Marionette.ItemView.extend({      

        template: addcontactleadTpl,

        events: {
          "click .ioptions #btn-edit": "editIssue",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.selectpicker').selectpicker();
        },

        editIssue: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          this.trigger("edit", this.model);
          //alert("Head to latest article");
          //this.trigger("edit:division", this);
        }
    });

    View.SearchHeader = Marionette.ItemView.extend({      

        template: searchheadTpl,

        events: {
          "click #search": "search",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          //$('.selectpicker').selectpicker();
        },

        search: function(e) { 
          e.preventDefault();
          e.stopPropagation();

          if (!navigator.contacts) {
            alert("Contacts API not supported", "Error");
            return;
          }
          var searchStr = $('#searchtext').val();
          var searchOptions = {
            filter : searchStr,
            multiple : true,
          };
          var contactFields = ['displayName', 'name', 'nickname'];
          navigator.contacts.find(contactFields, this.onSuccess, this.onError, searchOptions);
          /*var m = [];
          var c = {};
          c.displayName = 'Alex Mbaka';
          c.phoneNumbers = [];
          c.phoneNumbers.push('042555555');
          m.push(c);
          var d = {};
          d.displayName = 'Alex Mbaka';
          d.phoneNumbers = [];
          d.phoneNumbers.push('042555555');
          m.push(d);
          this.onSuccess(m);*/
          //this.trigger("edit", this.model);
          //alert("Head to latest article");
          //this.trigger("edit:division", this);
        },

        onSuccess: function (contacts){
          alert(contacts.length + ' contacts found!');
          var results = [];
          var cont = {};
          for (var i = 0; i < contacts.length; i++) {
            cont.name = contacts[i].displayName || contacts[i].name.familyName + " "+ contacts[i].name.givenName;
            cont.phone = '';
            if(contacts[i].phoneNumbers != null) {
              var len = contacts[i].phoneNumbers.length;
              if(len > 0) {
                for(var j = 0; j < len; j++) {
                  cont.phone += contacts[i].phoneNumbers[j].value + ', ';
                }
              }
            }
            results.push(cont);
          };
          /*var m = [];
          var c = {};
          c.name = 'Alex Mbaka';
          c.phone = '042555555';
          m.push(c);
          var d = {};
          d.name = 'Alex Mbaka';
          d.phone = '042555555';
          m.push(d);*/
          var THAT = this;
          setTimeout(function () {
            alert(JSON.stringify(results));
            THAT.trigger("results", results);
          }, 2000);
          
          
          
        },

        onError: function (er){
          alert('contact search error');
        }
    });

    View.Contact = Marionette.ItemView.extend({      

        template: contactTpl,

        tagName: "li",

        events: {
          "click #btn-read": "itemClicked"
        },

        itemClicked: function(e) {
          e.preventDefault();
          e.stopPropagation();
          this.trigger("submit", this.model);
        }
    });

    View.Contacts = Marionette.CompositeView.extend({

      className: "col-md-12",

      template: contactsTpl,

      itemView: View.Contact,

      itemViewContainer: "ul"
    });

  });

  return System.LeadsApp.Show.View;
});
