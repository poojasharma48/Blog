/**
 * Installation
*/

* install module by following steps:
   unzip the blog module and place in custom module
   
* Enable "Blog" module as usual.

* After installation , To add Blog content from custom form : https://your_domain_name/add/blog

* Before save data from blog custom form  need to add blog content type form admin with following fields:
   - Add field with name "field_blog_img" and type image type field and set required from backend

   - Add field with name "field_read_more" and disable title field of link and enable external link validation and set required from backend

   - set the body field required 

* Drush command to send mail notification
   drush d9-ns
