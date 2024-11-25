<?php
// app/Traits/TracksUserActions.php
namespace App\Traits;

use App\Models\UserAction;

trait TracksUserActions
{
    public function logAction($actionType, $description, $model = null, $oldValues = null, $newValues = null)
    {
        UserAction::create([
            'user_id' => auth()->id(),
            'action_type' => $actionType,
            'description' => $description,
            'actionable_type' => $model ? get_class($model) : null,
            'actionable_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
