## Important design decisions

[position, active, purchasable, terminal] fields are moved to ItemEntity to avoid code duplication 
when creating new ItemTypes.
[internal_name] - has to be implemented by concrete Items, for searching in ManyToOne relationships.


