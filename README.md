# UpAssist Neos Frontend login

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
