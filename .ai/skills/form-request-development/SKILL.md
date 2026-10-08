---
name: form-request-development
description: "Use this skill when creating, editing, or reviewing Laravel Form Requests, including request validation, authorization, enum-based validation, and API docs generator compatibility."
---

# Form Request Development

Standing constraints live in `.ai/rules/requests.md` (and controller authorization in `.ai/rules/controllers.md`). This skill is the create/edit/review workflow.

## When to use this skill

- Creating or refactoring a Form Request.
- Changing validation rules or request authorization.
- Working with enum-based validation or API docs generator compatibility.

## Creating a Form Request

1. Identify the domain and the route operation (`Store`, `Update`, `Index`, `Destroy`, …).
2. Check existing Form Requests in the same domain.
3. Follow `.ai/rules/requests.md` for directory layout, naming, and base class.
4. Add the class under `app/Http/Requests/{DomainName}/`.

```text
app/Http/Requests/Order/OrderStoreRequest.php
app/Http/Requests/Order/OrderUpdateRequest.php
```

5. Implement `rules()`. For payload-dependent authorization, override `authorize()` only when the rule file requires it — example:

```php
public function authorize(): bool
{
    $orderId = $this->input('order_id');
    $order = Order::query()->find($orderId);

    return $order && $this->user()->can('update', $order);
}
```

## Reviewing

1. Read `.ai/rules/requests.md`, then the Form Request, its Policy, `BaseRequest`, and sibling requests in the same domain.
2. Preserve existing conventions unless the change requires a new pattern.
3. Confirm every request field stays explicit for docs generators.

## Testing

When validation or authorization behavior changes, update the Form Request tests. Follow `.ai/rules/tests.md` and the `testing-best-practices` skill.
