define(["app_admin"], function(TheMarket){
  TheMarket.module('Entities', function(Entities, TheMarket, Backbone, Marionette, $, _){

    Backbone.emulateJSON = true;

    Entities.Journal = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/journal",
    });

    Entities.JournalCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/journals",
      model: Entities.Journal
    });

    Entities.Issue = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/journal",
    });

    Entities.IssueCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/journals",
      model: Entities.Issue
    });

    Entities.Article = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/journal",
    });

    Entities.ArticleCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/journals",
      model: Entities.Article
    });

    Entities.Indicator = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/trend",
    });

    Entities.IndicatorCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/trends",
      model: Entities.Indicator
    });

    Entities.Blog = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/blog",
    });

    Entities.BlogCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/blogs",
      model: Entities.Blog
    });

    Entities.BlogComment = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/blog",
    });

    Entities.BlogCommentCollection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/blogs",
      model: Entities.BlogComment
    });

    Entities.Model = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/blog",
    });

    Entities.Collection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/blogs",
      model: Entities.Model
    });

    //Entities.configureStorage(Entities.Journal);

    //Entities.configureStorage(Entities.JournalCollection);

    var initializeModels = function(val){
      var collection = new Entities.Collection(val);
      return collection.models;
    };

    var initializeJournal = function(){
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
        //console.log(journal);
        journal.save();
      });
      return journals.models;
    };

     var initializeIssue = function(){
      var issues = new Entities.IssueCollection([
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Fall 2014","article_count":"6","date_added":"6-5-2014"},
        {"issue":"Spring 2014","article_count":"3","date_added":"6-8-2014"}
      ]);
      //issues.forEach(function(issue){
        //console.log(issue);
        //issue.save();
      //});
      return issues.models;
    };

    var initializeArticle = function(){
      var articles = new Entities.ArticleCollection([
        {"article_id":"12","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"22","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"32","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"42","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"52","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"62","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"72","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"82","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"},
        {"article_id":"93","title":"Molecular Genetics and Economics","author":"Beauchamp, J. P., D. Cesarini, M. Johannesson, M. J. H. M. van der Loos, P. D. Koellinger, P. J. F. Groenen, J. H. Fowler, J. N. Rosenquist, A. R. Thurik & N. A. Christakis","abstract":"The costs of comprehensively genotyping human subjects have fallen to the point where major funding bodies, even in the social sciences, are beginning to incorporate genetic and biological markers into major social surveys. How, if at all, should economists use and combine molecular genetic and economic data from these surveys? What challenges arise when analyzing genetically informative data? To illustrate, we present results from a genome-wide association study of educational attainment. We use a sample of 7,500 individuals from the Framingham Heart Study; our dataset contains over 360,000 genetic markers per person. We get some initially promising results linking genetic markers to educational attainment, but these fail to replicate in a second large sample of 9,500 people from the Rotterdam Study. Unfortunately such failure is typical in molecular genetic studies of this type, so the example is also cautionary. We discuss a number of methodological challenges that face researchers who use molecular genetics to reliably identify genetic associates of economic traits. Our overall assessment is cautiously optimistic: this new data source has potential in economics. But researchers and consumers of the genoeconomic literature should be wary of the pitfalls, most notably the difficulty of doing reliable inference when faced with multiple hypothesis problems on a scale never before encountered in social science.","category_id":"3"}
      ]);
      //articles.forEach(function(issue){
        //console.log(issue);
        //issue.save();
      //});
      return articles.models;
    };

    var initializeIssues = function(val, journalId, journalName){
      var issues = new Entities.IssueCollection(val);
      issues.forEach(function(issue){
        issue.set('journal_id', journalId);
        issue.set('journal_name', journalName);
      });
      return issues.models;
    };

    var initializeArticles = function(val){
      var issues = new Entities.IssueCollection(val);
      //issues.forEach(function(issue){
        //issue.set('journal_id', journalId);
      //});
      return issues.models;
    };


    var initializeJournals = function(val){
      var journals = new Entities.JournalCollection(val);
      //journals.forEach(function(journal){
        //journal.save();
      //});
      return journals.models;
    };

    var initializeIndicators = function(val){
      var indicators = new Entities.IndicatorCollection(val);
      //indicators.forEach(function(indicator){
        //indicator.save();
      //});
      return indicators.models;
    };

    var initializeBlogs = function(val){
      var blogs = new Entities.BlogCollection(val);
      //blogs.forEach(function(blog){
        //blog.save();
      //});
      return blogs.models;
    };

    var initializeBlogComments = function(val){
      var blogcomments = new Entities.BlogCommentCollection(val);
      //blogcommentss.forEach(function(blog){
        //blog.save();
      //});
      return blogcomments.models;
    };

    var blogs, currentBlog;

    var API = {
      getJournalEntities: function(){
        var journals = new Entities.JournalCollection();
        var defer = $.Deferred();

        journals.fetch({
          success: function(data){
            //alert(JSON.parse(data));
            defer.resolve(data);
          }
        });
        var promise = defer.promise();
        $.when(promise).done(function(journals){
          if(journals.length === 0){
            $.get("/ecomadmin/presentation/journals/?journals", function(val){
              if(val.length === 0){
                // if we don't have any journals yet, create some for convenience
                var models = initializeJournal();
                journals.reset(models);
              }else{
                var models = initializeJournals(JSON.parse(val));
                journals.reset(models);
              }              
            });
          }
        });
        return journals;
      },

      getJournalIssues: function(model){
        var journalId = model.get('journal_id');
        var journalName = model.get('name');
        var issues = new Entities.IssueCollection();
        var defer = $.Deferred();        

        $.when(

          $.get("/ecomadmin/presentation/journals/?issues&journalId=" + journalId, function(val){
              var m = JSON.parse(val);
              //alert(m.length);
              if(m.length === 0){
                // if we don't have any issues yet, create some for convenience
                var models = initializeIssues(m, journalId, journalName);
                issues.reset(models);
              }else{
                var models = initializeIssues(m, journalId, journalName);
                issues.reset(models);
              }              
          })

        ).done(function() {

          model.set('issues', issues);
          //alert(JSON.stringify(model));
          defer.resolve(model);
        });
        return defer.promise();
      },

      getIssueArticles: function(model){
        var journalId = model.get('journal_id');
        
        var issue = model.get('issue');
        var articles = new Entities.ArticleCollection();
        var defer = $.Deferred();        

        $.when(

          $.get("/ecomadmin/presentation/journals/?issue=" + issue + "&journalId=" + journalId, function(val){
              var m = JSON.parse(val);
              //alert(m.length);
              if(m.length === 0){
                // if we don't have any articles yet, create some for convenience
                var models = initializeArticles(m, journalId)
                articles.reset(models);
              }else{
                var models = initializeArticles(m, journalId);
                articles.reset(models);
              }              
          })

        ).done(function() {

          model.set('articles', articles);
          defer.resolve(model);
        });
        return defer.promise();
      },

      getIndicatorEntities: function(){
        var indicators = new Entities.IndicatorCollection();
        var defer = $.Deferred();

        indicators.fetch({
          success: function(data){
            //alert(JSON.parse(data));
            defer.resolve(data);
          }
        });
        var promise = defer.promise();
        $.when(promise).done(function(indicators){
          if(indicators.length === 0){
            $.get("/ecomadmin/presentation/trends", function(val){
              if(val.length === 0){
                // if we don't have any indicators yet, create some for convenience
                var models = initializeIndicator();
                indicators.reset(models);
              }else{
                alert(val)
                var models = initializeIndicators(JSON.parse(val));
                indicators.reset(models);
              }              
            });
          }
        });
        return indicators;
      },

      getBlogEntities: function(){
        blogs = new Entities.BlogCollection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/blogs", function(val){
            var models = initializeBlogs(JSON.parse(val));
            blogs.reset(models);       
          })

        ).done(function() {
          defer.resolve(blogs);
        });
        return defer.promise();
        
      },

      getSubscriberEntities: function(){
        var subscribers = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?subscribers", function(val){
            var models = initializeModels(JSON.parse(val));
            subscribers.reset(models);       
          })

        ).done(function() {
          defer.resolve(subscribers);
        });
        return defer.promise();
        
      },

       getAdminEntities: function(){
        var admins = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?admins", function(val){
            var models = initializeModels(JSON.parse(val));
            admins.reset(models);       
          })

        ).done(function() {
          defer.resolve(admins);
        });
        return defer.promise();
        
      },

      searchSubscribers: function(name){
        var subscribers = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?searchSubscriber&name="+name, function(val){
            var models = initializeModels(JSON.parse(val));
            subscribers.reset(models);       
          })

        ).done(function() {
          defer.resolve(subscribers);
        });
        return defer.promise();
        
      },

      searchAdmins: function(name){
        var admins = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?searchAdmin&name="+name, function(val){
            var models = initializeModels(JSON.parse(val));
            admins.reset(models);       
          })

        ).done(function() {
          defer.resolve(admins);
        });
        return defer.promise();
        
      },

      filterSubscribers: function(key){
        var subscribers = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?filterSubscriber&key="+key, function(val){
            var models = initializeModels(JSON.parse(val));
            subscribers.reset(models);       
          })

        ).done(function() {
          defer.resolve(subscribers);
        });
        return defer.promise();
        
      },

      filterAdmins: function(key){
        var admins = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?filterAdmin&key="+key, function(val){
            var models = initializeModels(JSON.parse(val));
            admins.reset(models);       
          })

        ).done(function() {
          defer.resolve(admins);
        });
        return defer.promise();
        
      },

       getCategoryEntities: function(){
        categories = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/journals/?categories", function(val){
            var models = initializeModels(JSON.parse(val));
            categories.reset(models);       
          })

          /*$.get("/ecomadmin/presentation/journals/?categoricalCounts", function(val){
            var models = initializeModels(JSON.parse(val));
            categories.reset(models);       
          })*/

        ).done(function() {

          defer.resolve(categories);
        });
        return defer.promise();
        
      },

      /*getBlog: function(blogId){
        var blogcomments = new Entities.BlogCommentCollection();
        var blogs = new Entities.BlogCollection();
        var model;
        //alert(model);
        
        $.get("/ecomadmin/presentation/blogs", function(val){
          var models = initializeBlogs(JSON.parse(val));
          blogs.reset(models);
          model = blogs.at(blogId);
          alert(JSON.stringify(model));

          $.get("/ecomadmin/presentation/blogs/?blogId=" + blogId, function(full){
            model.set('full', full);

            $.get("/ecomadmin/presentation/blogs/?comments=" + blogId, function(res){
              if(res.length === 0){
                blogcomments.reset();
              }else{
                //alert(res)
                var models = initializeBlogComments(JSON.parse(res));
                blogcomments.reset(models);
                model.set('comments', blogcomments)
                //return model;
              }              
            });
          });
        });
        alert(JSON.stringify(model));
        return model;
      },*/

      getBlog: function(model){
        var blogcomments = new Entities.BlogCommentCollection();
        var blogId = model.get('blog_id');
        var defer = $.Deferred();
        var fullBlog;

        $.when(

          $.get("/ecomadmin/presentation/blogs/?blogId=" + blogId, function(full){
            fullBlog = full;       
          }),

          $.get("/ecomadmin/presentation/blogs/?comments=" + blogId, function(res){
            var models = initializeBlogComments(JSON.parse(res));
            blogcomments.reset(models);          
          })

        ).done(function() {
          model.set('full', fullBlog);
          model.set('comments', blogcomments);
          defer.resolve(model);
        });
        return defer.promise();
      },

      getAccountDetails: function(){
        var account;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?adminDetails", function(val){
            account = new Entities.Model(JSON.parse(val));      
          })

        ).done(function() {
          defer.resolve(account);
        });
        return defer.promise();
        
      },

      getDashboardEntities: function(){
        var dash;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?dashDetails", function(val){
            dash = new Entities.Model(JSON.parse(val));      
          })

        ).done(function() {
          defer.resolve(dash);
        });
        return defer.promise();
        
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

    TheMarket.reqres.setHandler("load:entities", function(){
      API.loadEntities();
      return;
    });

    TheMarket.reqres.setHandler("journal:entities", function(){
      return API.getJournalEntities();
    });

    TheMarket.reqres.setHandler("category:entities", function(){
      return API.getCategoryEntities();
    });

    TheMarket.reqres.setHandler("journal:issues", function(model){
      return API.getJournalIssues(model);
    });

    TheMarket.reqres.setHandler("issue:articles", function(model){
      return API.getIssueArticles(model);
    });

    TheMarket.reqres.setHandler("indicator:entities", function(){
      return API.getIndicatorEntities();
    });

    TheMarket.reqres.setHandler("blog:entities", function(){
      return API.getBlogEntities();
    });

    TheMarket.reqres.setHandler("blog:entity", function(model){
      return API.getBlog(model);
    });

    TheMarket.reqres.setHandler("subscriber:entities", function(){
      return API.getSubscriberEntities();
    });

    TheMarket.reqres.setHandler("subscribers:search", function(name){
      return API.searchSubscribers(name);
    });

    TheMarket.reqres.setHandler("subscribers:filter", function(key){
      return API.filterSubscribers(key);
    });

    TheMarket.reqres.setHandler("admin:entities", function(){
      return API.getAdminEntities();
    });

    TheMarket.reqres.setHandler("admins:search", function(name){
      return API.searchAdmins(name);
    });

    TheMarket.reqres.setHandler("admins:filter", function(key){
      return API.filterAdmins(key);
    });

    TheMarket.reqres.setHandler("account:entities", function(){
      return API.getAccountDetails();
    });

    TheMarket.reqres.setHandler("dashboard:entities", function(){
      return API.getDashboardEntities();
    });

    TheMarket.reqres.setHandler("journal:entity", function(id){
      return API.getJournalEntity(id);
    });

    TheMarket.reqres.setHandler("journal:entity:new", function(id){
      return new Entities.Journal();
    });

  });

  return ;
});