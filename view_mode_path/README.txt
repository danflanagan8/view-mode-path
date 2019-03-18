The point of this module is to make it easy to open any view mode of a node
or media entity in a modal.

It leverages existing Drupa/jquery infrastructure heavily. This article really
helped us get started on this: https://befused.com/drupal/modal

This module does two essential things to make view mode modals easy:
1. The module exposes routes for any node or media entity in any view mode.
   See the routing yml for the gory details.
   You can get the url object for any entity by passing the entity to
   view_mode_path_get_url. You could use the routes in Twig templates by leveraging
   path() of link().

2. The module provides a suite of field formatter plugins that modify the existing
   "link to content" behavior to instead open the content in a modal.
   - String fields can open the fields node/media in a modal
   - Media reference fields can use the media thumbnail to open the field's node/media
     or the referenced media itself in a modal.
   - Entity Reference labels can open the referenced node/media in a modal.
