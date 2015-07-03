# concrete5 Deeply

A handy tool to view and analyze the internal data of concrete5

### Sample session

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