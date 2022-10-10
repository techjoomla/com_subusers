# com_subusers

# Introduction
Subusers is a powerful component that provides the facility to associate roles to users. Based on the defined roles, users gets access to perform different actions in the organisation. Subusers is usually  integrated with multiagency or clusters. It cannot work on it own. It has to be integrated with any one of the components.

Based on the action mentioned in the RBACL, role hierarchy gets defined. The higher role must have more actions.

# Features

Below features makes subusers a powerful component to use-

1. Roles Management
Subusers allows the user to introduce / add new role in the system. Roles can be added from the backend.

Example - An agency can have roles like manager, admin, lead etc.

2. Role Hierarchy
Subusers allows user to define role hierarchy. Hierarchy is defined by the actions each role have. The role with higher actions count is considered to be superior role.

Example - An agency can have roles like manager, admin, lead etc. Admin can perfom 10 actions while manager can perform 8 actions, this defines that admin role is higher than manager in terms of hierarchy.

3. User Role Association
Subusers allows you to add roles to users. An agency can have multiple user roles and those roles can managed in this component quite easily.

Example - A user can be a manager in one agency and Admin in another.

4. Data access security
Subusers allows you have multiple user roles within the agencies but with this, the component keeps check on user that user should access the data only for the agency where he has access to.

# Syntax to use

RBACL::check(userId, recordClient, action, actionClient = null, contentId = null)
