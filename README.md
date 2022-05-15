Here are the details of the assignment:

Create a new content type "Blog" via admin.

Fields for the content type are:

	Title (Required)

	Body (Required)

	Read more link:  (Required) Link to external domain

	Cover Image:  (Required) Max dimension is 200px by 350px and max upload size is 250 KB. No minimum dimension restriction will be there.

Create a custom form to create a new blog node from the front-end with all backend validations.

When a new blog is created (either from admin or via a custom form), add a record in a custom table.

Table structure:

	nid

	created

	updated

    notification_status, ( default = 0 )

Write a Drush command that picks up the first 5 entries with notification_status as 0 and sends an email to the author of the node. When email is sent, set notification_status = 1
