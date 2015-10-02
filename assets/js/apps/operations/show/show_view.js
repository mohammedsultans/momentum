define(["app", "tpl!apps/templates/project.tpl", "tpl!apps/templates/editproject.tpl", "tpl!apps/templates/quotation.tpl", 
  "tpl!apps/templates/workreport.tpl", "tpl!apps/templates/billables.tpl", "backbone.syphon"], 
	function(System, projectTpl, editProjectTpl, quotationTpl, workReportTpl, billablesTpl){
  System.module('OperationApp.Show.View', function(View, System, Backbone, Marionette, $, _){   

    View.CreateQuote = Marionette.ItemView.extend({      

        template: quotationTpl,

        events: {
          "click .qadd": "addToQuote",
          "click .qdiscard": "discardQuote",
          "click .qgenerate": "generateQuote"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          this.setup();

          this['qitems'] = [];
          this['tax'] = 0;
          this['totalamt'] = 0;
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          var uls = $('#services');
          uls.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/operations/index.php?services', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="0">Select One...</option>');
            tp.appendTo(uls);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-question-circle" value="'+elem['name']+'">'+elem['name']+'</option>');
              tpl.appendTo(uls);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          var ulx = $('tbody');
          ulx.empty();
        },

        addToQuote: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          var data = Backbone.Syphon.serialize($("#frmq2")[0]);
          //_.extend(data, data2);
          //alert(JSON.stringify(data));
          var ar = [];
          ar = this['qitems'];
          ar.push(data);
          this['qitems'] = ar;

          var ul = $('tbody');

          var total = parseInt(data['qty']) * parseFloat(data['price']);
          this['totalamt'] += parseFloat(total);
          this['tax'] += parseFloat(total * parseInt(data['tax'])/100);

          $('#tottax').val(this['tax']);
          $('#totamt').val(this['totalamt']);

          var tpl = $('<tr><td>'+data['service']+'<br><span style="font-style:italic; font-size:11px">'+data['task']+'</span></td>'+
                      '<td>'+data['price']+'</td><td>'+data['qty']+'</td><td>Ksh. '+total+'</td></tr>');

          tpl.appendTo(ul);
          $('#services option[value="0"]').prop('selected', true);
          
          setTimeout(function (){
            $("#frmq2").find('input').val('');
            $('.selectpicker').selectpicker('refresh');
          }, 150);
        },

        isInt: function (value){
          return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseInt(value, 10));
        },

        generateQuote: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          data['client'] = parseInt(data['client'], 10);
          if (data['client']) {
            data['items'] = this['qitems'];
            //alert(JSON.stringify(data));
            this.trigger("generate", data);
          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        onSuccess: function(e) { 
          swal("Success!", "The quotation has been generated.", "success");
          $('#tottax').val('');
          $('#totamt').val('');
          var ulx = $('tbody');
          ulx.empty();
          //Open printable quote in separate window
        },

        onError: function(e) { 
          swal("Error!", "Quotation generation failed! Try again later.", "error");
        }
    });

    View.NewProject = Marionette.ItemView.extend({      

        template: projectTpl,

        events: {
          "click .iquote": "importQuote",
          "click .pdiscard": "discardProject",
          "click .psave": "createProject",
          "change #clients": "fetchQuotes",
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
          this['activities'] = [];
          this['quotes'] = [];
          this['pquotes'] = [];
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
          var ult = $('#quotes');
          ult.empty();
          var ulx = $('tbody.acts');
          ulx.empty();
        },

        fetchQuotes: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['client'] = parseInt(data['client'], 10)
          if (data['client']) {
            var ul = $('#quotes');
            ul.empty();
            var ulx = $('tbody.acts');
            ulx.empty();
            var THAT = this;
            this['activities'] = [];
            this['quotes'] = [];
            $.get(System.coreRoot + '/service/operations/index.php?quotes&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              var tp = $('<option data-icon="fa fa-calculator" value="0">Select Quotation...</option>');
              tp.appendTo(ul);
              THAT['pquotes'] = m;
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-calculator" value="'+elem['id']+'">QUOT-'+elem['id']+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

            
          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        importQuote: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp2")[0]);
          //_.extend(data, data2);
          data['quote'] = parseInt(data['quote'], 10);

          if (data['quote']) {
            var qt = [];
            qt = this['quotes'];
            
            if (!(_.contains(qt, data['quote']))) {
              qt.push(data['quote']);
              this['quotes'] = qt;

              var ul = $('tbody.acts');

              var q = this['pquotes'];

              q.forEach(function(quote){
                  if (quote['id'] == data['quote']) {
                    var items = quote['lineItems'];
                
                    items.forEach(function(elem){
                      var tpl = $('<tr><td>'+elem['itemName']+'</td><td><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td></tr>');
                      tpl.appendTo(ul);
                    });

                    setTimeout(function() {
                      $("select[name=quote] option[value='"+data['quote']+"']").css('display', 'none');
                      $("select[name=quote]").val(0);  
                      $('.selectpicker').selectpicker('refresh');
                    }, 300);
                  };
              });
              /*$.get(System.coreRoot + '/service/operations/index.php?quote='+data['quote'], function(result) {                
                var m = JSON.parse(result);              
                var items = m['lineItems'];
                
                items.forEach(function(elem){
                  var tpl = $('<tr><td>'+elem['itemName']+'</td><td><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td></tr>');
                  tpl.appendTo(ul);
                });

                setTimeout(function() {
                  $("select[name=quote] option[value='"+data['quote']+"']").css('display', 'none');
                  $("select[name=quote]").val(0);  
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              });*/
            };
          }else{
            swal("Error!", "Select a quotation number!", "error");
          }
        },        

        createProject: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['client'] = parseInt(data['client'], 10)
          if (data['client'] && data['project'] && data['location']) {
            data['quotes'] = this['quotes'];
            //alert(JSON.stringify(data));
            this.trigger("create", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        onSuccess: function(e) { 
          swal("Success!", "The project has been created.", "success");
          $('form input').val('');
          $('form textarea').val('');
          var ulx = $('tbody.acts');
          ulx.empty();
          //Open printable quote in separate window
        },

        onError: function(e) { 
          swal("Error!", "Project creation failed! Try again later.", "error");
        }
    });

    View.ViewProject = Marionette.ItemView.extend({      

        template: editProjectTpl,

        events: {
          "click .iquote": "importQuote",
          "click .pdiscard": "deleteProject",
          "click .psave": "updateProject",
          "change #clients": "fetchParticulars",
          "change #projects": "fetchProject"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
          this['quotes'] = [];
          this['projects'] = [];
          this['pquotes'] = [];
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
          var uly = $('#projects');
          uly.empty();
          var ult = $('#quotes');
          ult.empty();
          var ulx = $('tbody.acts');
          ulx.empty();
        },

        fetchParticulars: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['client'] = parseInt(data['client'], 10)
          if (data['client']) {
            var ul = $('#projects');
            var ulp = $('#quotes');
            ul.empty();
            ulp.empty();
            var ulx = $('tbody.acts');
            ulx.empty();
            this['quotes'] = [];
            var THAT = this;
            $.get(System.coreRoot + '/service/operations/index.php?projects&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              THAT['projects'] = m;
              var tp = $('<option data-icon="fa fa-archive">Select Project...</option>');
              tp.appendTo(ul);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-archive" value="'+elem['id']+'">PRJ-'+elem['name']+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

            $.get(System.coreRoot + '/service/operations/index.php?quotes&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              THAT['pquotes'] = m;
              var tp = $('<option data-icon="fa fa-calculator" value="0">Select Quotation...</option>');
              tp.appendTo(ulp);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-calculator" value="'+elem['id']+'">QUOT-'+elem['id']+'</option>');
                tpl.appendTo(ulp);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

            $('#prjname').val('');
            $('#prjloc').val('');
            $('#prjdescr').val('');
            $('select[name=status]').val(0);

            
          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        fetchProject: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          //alert(JSON.stringify(data));
          data['projectid'] = parseInt(data['projectid'], 10);
          if (data['projectid']) {
            var ulx = $('tbody.acts');
            ulx.empty();
            this['quotes'] = [];

            var p = this['projects'];

            p.forEach(function(project){
              if (project['id'] == data['projectid']) {
                var quotes = project['quotations'];
                
                quotes.forEach(function(quote){
                  var items = quote['lineItems'];
                  items.forEach(function(elem) {
                    var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td><td>'+elem['status']+'</td></tr>');
                    tpl.appendTo(ulx);
                  });               
                });

                $('#prjname').val(project['name']);
                $('#prjloc').val(project['location']);
                $('#prjdescr').val(project['descr']);
                $('select[name=status]').val(project['status']);
                              
                setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              };
            });


            /*$.get(System.coreRoot + '/service/operations/index.php?project='+data['projectid'], function(result) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              var quotes = res['quotations'];
                
              quotes.forEach(function(quote){
                var items = quote['lineItems'];
                items.forEach(function(elem) {
                  var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td><td>'+elem['status']+'</td></tr>');
                  tpl.appendTo(ulx);
                });               
              });

              $('#prjname').val(res['name']);
              $('#prjloc').val(res['location']);
              $('#prjdescr').val(res['descr']);
              $('select[name=status]').val(res['status']);
                            
              setTimeout(function() {
                $('.selectpicker').selectpicker('refresh');
              }, 300);
            });*/

            
          }else{
            swal("Error!", "Select a project first!", "error");
          }
        },

        importQuote: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp2")[0]);
          //_.extend(data, data2);
          data['quote'] = parseInt(data['quote'], 10);

          if (data['quote']) {
            var qt = [];
            qt = this['quotes'];
            
            if (!(_.contains(qt, data['quote']))) {
              qt.push(data['quote']);
              this['quotes'] = qt;

              var ul = $('tbody.acts');

              var q = this['pquotes'];

              q.forEach(function(quote){
                  if (quote['id'] == data['quote']) {
                    var items = quote['lineItems'];
                
                    items.forEach(function(elem){
                      var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td><td>'+elem['status']+'</td></tr>');
                      tpl.appendTo(ul);
                    });

                    setTimeout(function() {
                      $("select[name=quote] option[value='"+data['quote']+"']").css('display', 'none');
                      $("select[name=quote]").val(0);  
                      $('.selectpicker').selectpicker('refresh');
                    }, 300);
                  };
              });

              
              /*$.get(System.coreRoot + '/service/operations/index.php?quote='+data['quote'], function(result) {                
                var m = JSON.parse(result);              
                var items = m['lineItems'];
                
                items.forEach(function(elem){
                  var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td><td>'+elem['status']+'</td></tr>');
                  tpl.appendTo(ul);
                });

                setTimeout(function() {
                  $("select[name=quote] option[value='"+data['quote']+"']").css('display', 'none');  
                  $("select[name=quote]").val(0); 
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              });*/
            };
          }else{
            swal("Error!", "Select a quotation number!", "error");
          }
        },        

        updateProject: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['client'] = parseInt(data['client'], 10);
          if (data['client'] && data['project'] && data['location']) {
            data['quotes'] = this['quotes'];
            //alert(JSON.stringify(data));
            this.trigger("update", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        deleteProject: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var THAT = this;
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['projectid'] = parseInt(data['projectid'], 10);
          if (data['projectid']) {
            //alert(JSON.stringify(data));
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this project!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                  THAT.trigger("delete", data);                            
                } else {
                  swal("Cancelled", "The project is secured :)", "error");
                }
              });
            
          }else{
            swal("Error!", "Select a project to delete!", "error");
          }
        },

        onSuccess: function(e) { 
          swal("Success!", "The project particulars have been updated.", "success");
        },

        onError: function(e) { 
          swal("Error!", "Project modification failed! Try again later.", "error");
        }
    });

    View.WorkReport = Marionette.ItemView.extend({      

        template: workReportTpl,

        events: {
          "change #clients": "fetchProjects",
          "change #projects": "setActivities",
          "click .rcharge": "chargeToProject",
          "click .rdiscard": "discardReport",
          "click .rsubmit": "submitReport"          
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();          
          this['projects'] = [];
          this['tasks'] = [];
          this['charges'] = [];
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          var ulb = $('#expenses');
          ulb.empty();
          var ulu = $('#employees1');
          ulu.empty();
          var ulv = $('#employees2');
          ulv.empty();

          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Expense"', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select category...</option>');
            tp.appendTo(ulb);

            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ulb);
            });
          });

          $.get(System.coreRoot + '/service/hrm/index.php?employees', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Employee...</option>');
            var tpk = $('<option data-icon="fa fa-user">Select Employee...</option>');
            tp.appendTo(ulu);
            tpk.appendTo(ulv);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+parseInt(elem['id'], 10)+'">'+elem['name']+'</option>');
              var tpj = $('<option data-icon="fa fa-user" value="'+parseInt(elem['id'], 10)+'">'+elem['name']+'</option>');
              tpl.appendTo(ulu);
              tpj.appendTo(ulv);
            });

            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          var uly = $('#projects');          
          var ult = $('#activities');
          var ulx = $('tbody');
          uly.empty();
          ult.empty();                  
          ulx.empty();
        },

        fetchProjects: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr1")[0]);
          data['client'] = parseInt(data['client'], 10)
          if (data['client']) {
            var ul = $('#projects');
            var ulp = $('#activities');
            var ulx = $('tbody');
            ul.empty();
            ulp.empty();            
            ulx.empty();
            this['charges'] = [];
            var THAT = this;
            $.get(System.coreRoot + '/service/operations/index.php?projects&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              THAT['projects'] = m;
              var tp = $('<option data-icon="fa fa-archive">Select Project...</option>');
              tp.appendTo(ul);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-archive" value="'+elem['id']+'">PRJ-'+elem['name']+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });
            
          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        setActivities: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr1")[0]);
          //alert(JSON.stringify(data));
          data['project'] = parseInt(data['project'], 10);
          if (data['project']) {
            var ul = $('#activities');
            var ulx = $('tbody');
            ul.empty();
            ulx.empty(); 
            this['charges'] = [];

            var p = this['projects'];

            p.forEach(function(project){
              if (project['id'] == data['project']) {
                var items = project['activities'];

                  items.forEach(function(elem) {
                    if (elem['status'] == 0) {
                      var tpl = $('<option data-icon="fa fa-lightbulb-o" value="'+elem['id']+'">'+elem['service']+' - <span style="font-style:italic; font-size:11px">'+elem['task']+'</span></option>');
                      tpl.appendTo(ul);
                    };
                    
                  }); 
                              
                setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              };
            });


            /*$.get(System.coreRoot + '/service/operations/index.php?project='+data['projectid'], function(result) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              var quotes = res['quotations'];
                
              quotes.forEach(function(quote){
                var items = quote['lineItems'];
                items.forEach(function(elem) {
                  var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td><td>'+elem['quantity']+'</td><td>'+elem['status']+'</td></tr>');
                  tpl.appendTo(ulx);
                });               
              });

              $('#prjname').val(res['name']);
              $('#prjloc').val(res['location']);
              $('#prjdescr').val(res['descr']);
              $('select[name=status]').val(res['status']);
                            
              setTimeout(function() {
                $('.selectpicker').selectpicker('refresh');
              }, 300);
            });*/

            
          }else{
            swal("Error!", "Select a project first!", "error");
          }
        },

        chargeToProject: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr2")[0]);
          data['claimant'] = parseInt(data['claimant'], 10);
          //_.extend(data, data2);

          if (data['claimant'] && data['description'] && data['category'] && data['amount']) {
            
            var exp = this['charges'];
            exp.push(data);
            this['charges'] = exp;
            
            var ul = $('tbody');

            var name = $("#employees2 option[value='"+data['claimant']+"']").text();
            var category = $("#expenses option[value='"+data['category']+"']").text();
            
            var tpl = $('<tr><td>'+name+'</td><td>'+data['description']+'</td><td>'+category+'</td><td>Ksh. '+data['amount']+'</td></tr>');
            tpl.appendTo(ul);            
          }else{
            swal("Error!", "Enter all details!", "error");
          }
        },        

        submitReport: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr1")[0]);
          data['project'] = parseInt(data['project'], 10);
          if (data['client'] && data['activity'] && data['personell'] && data['report']) {
            data['charges'] = this['charges'];
            //alert(JSON.stringify(data));
            this.trigger("file", data);
          }else{
            swal("Error!", "Enter all *required details!", "error");
          }
        },

        discardReport: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          swal({
                title: "Are you sure?",
                text: "You will not be able to save this report!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, discard it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: true,
                closeOnCancel: true
              },
              function(isConfirm){
                if (isConfirm) {
                  $('form input').val('');
                  $('form textarea').val('');
                  var ulx = $('tbody');
                  ulx.empty();                          
                } else {
                  
                }
              });
        },

        onSuccess: function(e) { 
          swal("Success!", "The report has been submitted.", "success");
          $('form input').val('');
          $('form textarea').val('');
          this.setup();
          //Open printable quote in separate window
        },

        onError: function(e) { 
          swal("Error!", "Report submission failed! Try again later.", "error");
        }
    });

    View.Billables = Marionette.ItemView.extend({      

        template: billablesTpl,

        events: {
          "click .screate": "create",
          "click .sdel": "deleteService"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();          
          this['services'] = [];
        },

        setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/operations/index.php?services', function(result) {
            var m = JSON.parse(result);
            $('.scount').text(m.length);
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'</td><td><p class="sid" style="display: none;">'+elem['name']+'</p><a class="btn btn-danger sdel" href="#"><i class="fa fa-trash"></i></a></tr>');
              tpl.appendTo(ul);
            });

            $('.sdel').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var name = $(this).parent().find('.sid').text();
              swal({
                title: "Are you sure?",
                text: "You will not be able to recover this service!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                  THAT.trigger("deleteServ", name);                             
                } else {
                  swal("Cancelled", "The service is secured :)", "error");
                }
              });
              
            });
          });
        },

        create: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          if (data['name']) {
            this.trigger("create", data);
          }else{
            swal("Error!", "Enter the service name!", "error");
          }
        },

        onDelete: function(e) { 
          swal("Deleted!", "The service has been deleted.", "success");
          this.setup();
        },

        onSuccess: function(e) { 
          swal("Success!", "The service has been created.", "success");
          $('form input').val('');
          this.setup();
          //Open printable quote in separate window
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
        }
    });
  });

  return System.OperationApp.Show.View;
});
