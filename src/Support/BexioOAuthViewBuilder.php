<?php

namespace CodebarAg\Bexio\Support;

class BexioOAuthViewBuilder
{
    /**
     * Build a generic OAuth result view for any outcome (success, error, multi-action).
     *
     * @param  string  $type  Alert type ('success', 'danger', 'warning', etc.)
     * @param  string  $title  Title for the view
     * @param  string  $message  Message for the view
     * @param  array|null  $action  Single action button (url/label/class), or null
     * @param  array|null  $actions  Multiple action buttons (array of arrays), or null
     * @param  int|null  $status  Optional HTTP status code (for errors)
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function build(
        string $type,
        string $title,
        string $message,
        ?array $action = null,
        ?array $actions = null,
        ?int $status = null
    ) {
        $data = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ];
        if ($action) {
            $data['action'] = $action;
        }
        if ($actions) {
            $data['actions'] = $actions;
        }
        if ($status) {
            return response()->view('bexio::oauth-result', $data, $status);
        }

        return view('bexio::oauth-result', $data);
    }
}
