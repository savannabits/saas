<?php

namespace Savannabits\Saas\Macros;

use Illuminate\Database\Schema\Blueprint;
use Savannabits\Saas\Contracts\DocStatus;

class FrameworkColumns
{
    public static function auditColumns(Blueprint $table): void
    {
        $table->foreignUuid('owner_id')->nullable()->constrained('users')->restrictOnDelete();
        $table->foreignUuid('modified_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignUuid('submitted_by')->nullable()->constrained('users')->restrictOnDelete();
        $table->foreignUuid('cancelled_by')->nullable()->constrained('users')->restrictOnDelete();
        $table->foreignUuid('recalled_by')->nullable()->constrained('users')->restrictOnDelete();

        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamp('recalled_at')->nullable();
    }

    public static function statusColumns(Blueprint $table): void
    {
        $table->boolean('is_cancelled')->default(false);
        $table->tinyInteger('doc_status')->default(DocStatus::DRAFT);
        $table->boolean('is_active')->default(true);
        //        $table->text('cancellation_reason')->nullable();
    }

    public static function reversalColumns(Blueprint $table): void
    {
        $table->boolean('is_reversed')->default(false);
        $table->foreignUuid('reversed_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('reversed_at')->nullable();
    }

    public static function dropReversalColumns(Blueprint $table): void
    {
        $table->dropColumn('is_reversed');
        $table->dropColumn('reversed_by');
        $table->dropColumn('reversed_at');
    }

    public static function dropAuditColumns(Blueprint $table): void
    {
        $table->dropColumn('owner_id');
        $table->dropColumn('modified_by');
        $table->dropColumn('submitted_by');
        $table->dropColumn('cancelled_by');
        $table->dropColumn('recalled_by');
        $table->timestamp('submitted_at');
        $table->timestamp('cancelled_at');
        $table->timestamp('recalled_at');
    }

    public static function dropStatusColumns(Blueprint $table): void
    {
        $table->dropColumn('is_cancelled');
        $table->dropColumn('doc_status');
        $table->dropColumn('is_active');
        //        $table->dropColumn('cancellation_reason');
    }

    public static function teamColumn(Blueprint $table): void
    {
        $table->foreignUuid('team_id')->constrained()->restrictOnDelete();
    }

    public static function teamCodeColumn(Blueprint $table): void
    {
        $table->string('code');
        $table->unique([
            'code',
            'team_id',
        ]);
    }

    public static function dropTeamColumn(Blueprint $table): void
    {
        $name = 'team_id';
        $table->dropColumn($name);
    }
}
