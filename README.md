Digest
===========
Send out a digest to your users

Contents
-----------

1. Features
2. ToDo
3. Plugin developers
4. Theme developers

1. Features
-----------
Sends mails with update information to your site or group members based on default or personal intervals (monthly, fortnightly, weekly or daily)

- site wide digest
- group digest
- html mail (or fallback to online link if not supported)
- sent digest can also be viewed online (not only in mail)
- extend/replace layout or content of digests with own views
- exclude groups from delivery with a hook

2. ToDo
-----------

3. Plugin developers
-----------
If you wish to supply content for either the Site digest of Group digest you can easily extend the digest.

### For the Site digest
Please extend the view "digest/elements/site" with the content from your plugin and
extend the view "css/digest/site" with the CSS that's part of this content.

You can find out which variables your have available in your view by checking the view "digest/elements/site".

We suggest you put your content in "digest/elements/site/<your pluginname>" and 
the CSS in "css/digest/site/<your plugin>".
This is just a suggestion, but it would make it easier for theme developers.

### For the Group digest
Please extend the view "digest/elements/group" with the content from your plugin and
extend the view "css/digest/group" with the CSS that's part of this content.

You can find out which variables your have available in your view by checking the view "digest/elements/group".

We suggest you put your content in "digest/elements/group/<your pluginname>" and 
the CSS in "css/digest/group/<your plugin>".
This is just a suggestion, but it would make it easier for theme developers.

4. Theme developers
-----------
If you want to adjust the Digest to look like your theme, please check to following elements

### Shell
The base layout of the digest can be found in the view "page/layouts/digest". The CSS that's part of the base layout can be found in the view "css/digest/core".

### Elements
The base layout consists of a few default elements

#### Header
In the view "digest/elements/header" this contains the title of the current digest.

#### Online link
In the view "digest/elements/online" this contains a link to the online view of the digest. In case the digest can't be viewed correctly in the e-mail client.

#### Content
The content of the digest will be made using the view view "digest/elements/site" for the Site digest or "digest/elements/group" for the Group digest.

Both view are just empty wrapper view, all plugins are required to extend to these views in order to display content.
 
#### Footer
In the view "digest/elements/footer" this contains a description of either the Site or the Group, depending on the digest.

#### Unsubscribe
In the view "digest/elements/unsubscribe" this contains some information about where the digest came from, how the user can change the settings of the digest 
and a direct link to unsubscribe from the digest.