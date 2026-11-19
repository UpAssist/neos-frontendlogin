# UpAssist Neos Frontend login

Frontend login, logout, registration, profile and password reset for Neos, plus a
`restrictNode` mixin that hides documents (and their children) from visitors who
are not logged in.

## Compatibility

| Package | Neos | PHP | Notes |
| --- | --- | --- | --- |
| 8.5.x | 8.3, 8.4 | 8.1+ | Tested in 8.3 and 8.4 |
| 8.5.x | 9.x | | Not supported: node restriction uses the Neos 8 `NodeData` privilege API |

Neos 9 support would need a rewrite of the node restriction (Neos 9 uses subtree
tags instead of `NodeData` privileges). It is only planned once a Neos 9 project
needs it.

Password reset and the optional admin notification send mail through
`neos/swiftmailer` (suggested, not required, so it does not pin
`egulias/email-validator`): require it and configure its transport in your project.

## Installation & Setup
Install using composer.

Set your _content_ constraints to allow the supertype:

```yaml
  constraints:
    nodeTypes:
      ...
      'UpAssist.Neos.FrontendLogin:Mixin.Abstract': true
```

To allow redirection, set the following to your _document_ nodes:

```yaml
  superTypes:
    ...
    'UpAssist.Neos.FrontendLogin:Mixin.RestrictNode': true
```
