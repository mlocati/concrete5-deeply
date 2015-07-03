# concrete5 Deeply

A handy tool to view and analyze the internal data of concrete5


### Sample session - Interactive

```
$ bin/c5deeply --webroot path/to/concrete5/webroot

/------------------------\
| 1. Search by block ID  |
|                        |
| x. Exit                |
\------------------------/

Your choice: 1
Block ID: 123
Block with ID 123
   Type handle: horizontal_rule
   Type name  : Horizontal Rule
   Block found here:
      Area "Main" of:
         Collection 160 @ version 1
            Name    : Contact
            Handle  : contact
            Approved: yes
            Path    : /contact
            comments: Initial Version

/------------------------\
| 1. Search by block ID  |
|                        |
| x. Exit                |
\------------------------/

Your choice: x
```

### Sample session - Immediate execution

```
$ bin/c5deeply --webroot path/to/concrete5/webroot --blocks=1,2,3

Block with ID 1
   Type handle: content
   Type name  : Content
   Block found here:
      Area "Main" of:
         Collection 126 @ version 1
            Name    : Welcome to concrete5
            Handle  : welcome
            Approved: yes
            Path    : /dashboard/welcome
            comments: Initial Version
Block with ID 2
   Type handle: dashboard_app_status
   Type name  : Dashboard App Status
   Block found here:
      Area "Primary" of:
         Collection 127 @ version 1
            Name    : Customize Dashboard Home
            Handle  : home
            Approved: yes
            Path    : /dashboard/home
            comments: Initial Version
Block with ID 3
   Type handle: dashboard_site_activity
   Type name  : Dashboard Site Activity
   Block found here:
      Area "Primary" of:
         Collection 127 @ version 1
            Name    : Customize Dashboard Home
            Handle  : home
            Approved: yes
            Path    : /dashboard/home
            comments: Initial Version
```