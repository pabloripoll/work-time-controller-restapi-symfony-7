<?php

namespace App\Domain\Employment\ValueObject;

enum EmploymentActionKey: string
{
    // ==========================================
    // CONTRACT ACTIONS
    // ==========================================

    case CONTRACT_CREATED = 'contract.created';
    case CONTRACT_UPDATED = 'contract.updated';
    case CONTRACT_DELETED = 'contract.deleted';
    case CONTRACT_RESTORED = 'contract.restored';

    // Contract signing
    case CONTRACT_SIGNED = 'contract.signed';
    case CONTRACT_UNSIGNED = 'contract.unsigned';

    // GDPR/LOPD compliance
    case GDPR_SIGNED = 'contract.gdpr_signed';
    case GDPR_REVOKED = 'contract.gdpr_revoked';
    case LOPD_SIGNED = 'contract.lopd_signed';
    case LOPD_REVOKED = 'contract.lopd_revoked';

    // Contract terms
    case CONTRACT_TERMS_UPDATED = 'contract.terms_updated';
    case CONTRACT_HOURS_UPDATED = 'contract.hours_updated';
    case CONTRACT_DAYS_UPDATED = 'contract.days_updated';

    // Contract type
    case CONTRACT_TYPE_CHANGED = 'contract.type_changed';

    // Contract status
    case CONTRACT_ACTIVATED = 'contract.activated';
    case CONTRACT_DEACTIVATED = 'contract.deactivated';
    case CONTRACT_SUSPENDED = 'contract.suspended';
    case CONTRACT_TERMINATED = 'contract.terminated';

    // ==========================================
    // WORKDAY ACTIONS
    // ==========================================

    case WORKDAY_CREATED = 'workday.created';
    case WORKDAY_UPDATED = 'workday.updated';
    case WORKDAY_DELETED = 'workday.deleted';
    case WORKDAY_RESTORED = 'workday.restored';

    // Extra hours management (admin only)
    case WORKDAY_HOURS_EXTRA_ADDED = 'workday.hours_extra_added';
    case WORKDAY_HOURS_EXTRA_UPDATED = 'workday.hours_extra_updated';
    case WORKDAY_HOURS_EXTRA_REMOVED = 'workday.hours_extra_removed';

    // Workday times
    case WORKDAY_START_TIME_CORRECTED = 'workday.start_time_corrected';
    case WORKDAY_END_TIME_CORRECTED = 'workday.end_time_corrected';
    case WORKDAY_HOURS_MADE_RECALCULATED = 'workday.hours_made_recalculated';

    // Workday approval
    case WORKDAY_APPROVED = 'workday.approved';
    case WORKDAY_REJECTED = 'workday.rejected';
    case WORKDAY_PENDING_REVIEW = 'workday.pending_review';
    case WORKDAY_TIME_CORRECTED = 'workday.time_corrected';

    // ==========================================
    // CLOCKING ACTIONS
    // ==========================================

    case CLOCKING_CREATED = 'clocking.created';
    case CLOCKING_UPDATED = 'clocking.updated';
    case CLOCKING_DELETED = 'clocking.deleted';
    case CLOCKING_RESTORED = 'clocking.restored';

    // Clock in/out
    case CLOCKING_CLOCK_IN = 'clocking.clock_in';
    case CLOCKING_CLOCK_OUT = 'clocking.clock_out';

    // Manual entries (admin)
    case CLOCKING_MANUAL_ENTRY = 'clocking.manual_entry';
    case CLOCKING_MANUAL_CLOCK_IN = 'clocking.manual_clock_in';
    case CLOCKING_MANUAL_CLOCK_OUT = 'clocking.manual_clock_out';

    // Corrections (admin)
    case CLOCKING_TIME_CORRECTED = 'clocking.time_corrected';
    case CLOCKING_CLOCK_IN_CORRECTED = 'clocking.clock_in_corrected';
    case CLOCKING_CLOCK_OUT_CORRECTED = 'clocking.clock_out_corrected';

    // Missed clockings
    case CLOCKING_MISSED_CLOCK_OUT = 'clocking.missed_clock_out';
    case CLOCKING_MISSED_CLOCK_OUT_AUTO_ADDED = 'clocking.missed_clock_out_auto_added';

    // Disputed clockings
    case CLOCKING_DISPUTED = 'clocking.disputed';
    case CLOCKING_DISPUTE_RESOLVED = 'clocking.dispute_resolved';
    case CLOCKING_DISPUTE_REJECTED = 'clocking.dispute_rejected';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            // Contract actions
            self::CONTRACT_CREATED => 'Contract created',
            self::CONTRACT_UPDATED => 'Contract updated',
            self::CONTRACT_DELETED => 'Contract deleted',
            self::CONTRACT_RESTORED => 'Contract restored',
            self::CONTRACT_SIGNED => 'Contract signed',
            self::CONTRACT_UNSIGNED => 'Contract unsigned',
            self::GDPR_SIGNED => 'GDPR consent signed',
            self::GDPR_REVOKED => 'GDPR consent revoked',
            self::LOPD_SIGNED => 'LOPD consent signed',
            self::LOPD_REVOKED => 'LOPD consent revoked',
            self::CONTRACT_TERMS_UPDATED => 'Contract terms updated',
            self::CONTRACT_HOURS_UPDATED => 'Contract hours updated',
            self::CONTRACT_DAYS_UPDATED => 'Contract days updated',
            self::CONTRACT_TYPE_CHANGED => 'Contract type changed',
            self::CONTRACT_ACTIVATED => 'Contract activated',
            self::CONTRACT_DEACTIVATED => 'Contract deactivated',
            self::CONTRACT_SUSPENDED => 'Contract suspended',
            self::CONTRACT_TERMINATED => 'Contract terminated',

            // Workday actions
            self::WORKDAY_CREATED => 'Workday created',
            self::WORKDAY_UPDATED => 'Workday updated',
            self::WORKDAY_DELETED => 'Workday deleted',
            self::WORKDAY_RESTORED => 'Workday restored',
            self::WORKDAY_HOURS_EXTRA_ADDED => 'Extra hours added',
            self::WORKDAY_HOURS_EXTRA_UPDATED => 'Extra hours updated',
            self::WORKDAY_HOURS_EXTRA_REMOVED => 'Extra hours removed',
            self::WORKDAY_START_TIME_CORRECTED => 'Start time corrected',
            self::WORKDAY_END_TIME_CORRECTED => 'End time corrected',
            self::WORKDAY_HOURS_MADE_RECALCULATED => 'Total hours recalculated',
            self::WORKDAY_APPROVED => 'Workday approved',
            self::WORKDAY_REJECTED => 'Workday rejected',
            self::WORKDAY_PENDING_REVIEW => 'Workday pending review',
            self::WORKDAY_TIME_CORRECTED => 'Workday time corrected',

            // Clocking actions
            self::CLOCKING_CREATED => 'Clocking record created',
            self::CLOCKING_UPDATED => 'Clocking record updated',
            self::CLOCKING_DELETED => 'Clocking record deleted',
            self::CLOCKING_RESTORED => 'Clocking record restored',
            self::CLOCKING_CLOCK_IN => 'Employee clocked in',
            self::CLOCKING_CLOCK_OUT => 'Employee clocked out',
            self::CLOCKING_MANUAL_ENTRY => 'Manual clocking entry',
            self::CLOCKING_MANUAL_CLOCK_IN => 'Manual clock in added',
            self::CLOCKING_MANUAL_CLOCK_OUT => 'Manual clock out added',
            self::CLOCKING_TIME_CORRECTED => 'Clocking time corrected',
            self::CLOCKING_CLOCK_IN_CORRECTED => 'Clock in time corrected',
            self::CLOCKING_CLOCK_OUT_CORRECTED => 'Clock out time corrected',
            self::CLOCKING_MISSED_CLOCK_OUT => 'Missed clock out detected',
            self::CLOCKING_MISSED_CLOCK_OUT_AUTO_ADDED => 'Missed clock out automatically added',
            self::CLOCKING_DISPUTED => 'Clocking disputed by employee',
            self::CLOCKING_DISPUTE_RESOLVED => 'Clocking dispute resolved',
            self::CLOCKING_DISPUTE_REJECTED => 'Clocking dispute rejected',
        };
    }

    /**
     * Get category of the action
     */
    public function category(): string
    {
        return match($this) {
            self::CONTRACT_CREATED,
            self::CONTRACT_UPDATED,
            self::CONTRACT_DELETED,
            self::CONTRACT_RESTORED,
            self::CONTRACT_SIGNED,
            self::CONTRACT_UNSIGNED,
            self::GDPR_SIGNED,
            self::GDPR_REVOKED,
            self::LOPD_SIGNED,
            self::LOPD_REVOKED,
            self::CONTRACT_TERMS_UPDATED,
            self::CONTRACT_HOURS_UPDATED,
            self::CONTRACT_DAYS_UPDATED,
            self::CONTRACT_TYPE_CHANGED,
            self::CONTRACT_ACTIVATED,
            self::CONTRACT_DEACTIVATED,
            self::CONTRACT_SUSPENDED,
            self::CONTRACT_TERMINATED => 'contract',

            self::WORKDAY_CREATED,
            self::WORKDAY_UPDATED,
            self::WORKDAY_DELETED,
            self::WORKDAY_RESTORED,
            self::WORKDAY_HOURS_EXTRA_ADDED,
            self::WORKDAY_HOURS_EXTRA_UPDATED,
            self::WORKDAY_HOURS_EXTRA_REMOVED,
            self::WORKDAY_START_TIME_CORRECTED,
            self::WORKDAY_END_TIME_CORRECTED,
            self::WORKDAY_HOURS_MADE_RECALCULATED,
            self::WORKDAY_APPROVED,
            self::WORKDAY_REJECTED,
            self::WORKDAY_PENDING_REVIEW,
            self::WORKDAY_TIME_CORRECTED => 'workday',

            self::CLOCKING_CREATED,
            self::CLOCKING_UPDATED,
            self::CLOCKING_DELETED,
            self::CLOCKING_RESTORED,
            self::CLOCKING_CLOCK_IN,
            self::CLOCKING_CLOCK_OUT,
            self::CLOCKING_MANUAL_ENTRY,
            self::CLOCKING_MANUAL_CLOCK_IN,
            self::CLOCKING_MANUAL_CLOCK_OUT,
            self::CLOCKING_TIME_CORRECTED,
            self::CLOCKING_CLOCK_IN_CORRECTED,
            self::CLOCKING_CLOCK_OUT_CORRECTED,
            self::CLOCKING_MISSED_CLOCK_OUT,
            self::CLOCKING_MISSED_CLOCK_OUT_AUTO_ADDED,
            self::CLOCKING_DISPUTED,
            self::CLOCKING_DISPUTE_RESOLVED,
            self::CLOCKING_DISPUTE_REJECTED => 'clocking',
        };
    }

    /**
     * Check if action requires admin privileges
     */
    public function requiresAdmin(): bool
    {
        return match($this) {
            // Admin-only actions
            self::CONTRACT_UPDATED,
            self::CONTRACT_DELETED,
            self::CONTRACT_RESTORED,
            self::CONTRACT_UNSIGNED,
            self::GDPR_REVOKED,
            self::LOPD_REVOKED,
            self::CONTRACT_TERMS_UPDATED,
            self::CONTRACT_HOURS_UPDATED,
            self::CONTRACT_DAYS_UPDATED,
            self::CONTRACT_TYPE_CHANGED,
            self::CONTRACT_ACTIVATED,
            self::CONTRACT_DEACTIVATED,
            self::CONTRACT_SUSPENDED,
            self::CONTRACT_TERMINATED,
            self::WORKDAY_UPDATED,
            self::WORKDAY_DELETED,
            self::WORKDAY_RESTORED,
            self::WORKDAY_HOURS_EXTRA_ADDED,
            self::WORKDAY_HOURS_EXTRA_UPDATED,
            self::WORKDAY_HOURS_EXTRA_REMOVED,
            self::WORKDAY_START_TIME_CORRECTED,
            self::WORKDAY_END_TIME_CORRECTED,
            self::WORKDAY_HOURS_MADE_RECALCULATED,
            self::WORKDAY_APPROVED,
            self::WORKDAY_REJECTED,
            self::WORKDAY_TIME_CORRECTED,
            self::CLOCKING_UPDATED,
            self::CLOCKING_DELETED,
            self::CLOCKING_RESTORED,
            self::CLOCKING_MANUAL_ENTRY,
            self::CLOCKING_MANUAL_CLOCK_IN,
            self::CLOCKING_MANUAL_CLOCK_OUT,
            self::CLOCKING_TIME_CORRECTED,
            self::CLOCKING_CLOCK_IN_CORRECTED,
            self::CLOCKING_CLOCK_OUT_CORRECTED,
            self::CLOCKING_MISSED_CLOCK_OUT_AUTO_ADDED,
            self::CLOCKING_DISPUTE_RESOLVED,
            self::CLOCKING_DISPUTE_REJECTED => true,

            // Employee or system actions
            default => false,
        };
    }

    /**
     * Get severity level for logging/alerting
     */
    public function severity(): string
    {
        return match($this) {
            // Critical actions
            self::CONTRACT_DELETED,
            self::CONTRACT_TERMINATED,
            self::GDPR_REVOKED,
            self::LOPD_REVOKED,
            self::WORKDAY_DELETED,
            self::CLOCKING_DELETED => 'critical',

            // High severity
            self::CONTRACT_UPDATED,
            self::CONTRACT_TERMS_UPDATED,
            self::CONTRACT_HOURS_UPDATED,
            self::CONTRACT_TYPE_CHANGED,
            self::CONTRACT_SUSPENDED,
            self::WORKDAY_HOURS_EXTRA_ADDED,
            self::WORKDAY_TIME_CORRECTED,
            self::CLOCKING_TIME_CORRECTED,
            self::CLOCKING_MANUAL_ENTRY => 'high',

            // Medium severity
            self::CONTRACT_SIGNED,
            self::GDPR_SIGNED,
            self::LOPD_SIGNED,
            self::WORKDAY_APPROVED,
            self::WORKDAY_REJECTED,
            self::CLOCKING_DISPUTED => 'medium',

            // Low severity (normal operations)
            default => 'low',
        };
    }
}
