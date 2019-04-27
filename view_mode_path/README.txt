The point of this module is to make it easy to open any view mode of a node
or media entity in a modal.

It leverages existing Drupa/jquery infrastructure heavily. This article really
helped us get started on this: https://befused.com/drupal/modal

This module does three essential things to make view mode modals easy:
1. The module exposes ROUTES for any node or media entity in any view mode.
   See the routing yml for the gory details.
   - You can get the url object for any entity by passing the entity to
   view_mode_path_get_url.
   - You could use the routes in Twig templates by leveraging path().
   This module provides easier ways, but for example you could do this:

   <a class="use-ajax" data-dialog-type="modal" data-dialog-options="{&quot;width&quot;: &quot;750&quot;, &quot;dialogClass&quot;: &quot;my-class&quot;}" href="{{ path('view_mode_path.node'), {'id': node.id, 'view_mode': 'teaser'})}}">My link that opens a teaser in a modal"</a>

2. The module provides a suite of FIELD FORMATTER plugins that modify the existing
   "link to content" behavior to instead open the content in a modal.
   - String fields can open the fields node/media in a modal
   - Media reference fields can use the media thumbnail to open the field's node/media
     or the referenced media itself in a modal.
   - Entity Reference labels can open the referenced node/media in a modal.
   - Image fields can open the content in a modal. The file itself cannot be opened
     in a modal. That functionality could be added by giving file entities a route
     and then editing view_mode_path_get_url.

3. The module provides a TWIG FUNCTION to easily create links that open entities
   in modals in any view mode. The function is view_mode_path_modal_link_attributes.
   It takes four params: entity, view_mode, modal width, classes for the anchor,
   and classes for the modal.

   <a {{ view_mode_path_modal_link_attributes(node, 'teaser', '750', 'my-anchor-class', 'my-modal-class') }}>Open teaser in modal!</a>

   The only required parameter is the entity. If other settings are set to NULL
   or are left out entirely, the defaults from view_mode_path.settings will be
   used (if they exist). So it could be as simple as this:

   <a {{ view_mode_path_modal_link_attributes(node) }}>Open default view mode in modal!</a>

4. Bonus! This one's in here even though it doesn't have to do with view modes.
   It just allows non-node and non-media routes to open easily in a modal using
   the same default config set in this module.

   <a {{ view_mode_path_modal_link_by_route_attributes('view.some_view.page_1', [], '75%', 'my-anchor-class', 'my-modal-class') }}>Open view in modal!</a>
