<?php

function currentOwnerId()
{
    $user = auth()->user();

    if (!$user) return null;

    if ($user->u_type == 4) return $user->ca_add_by ?: $user->id;
    if ($user->u_type == 5) return $user->user_add_by ?: $user->id;
    if ($user->u_type == 6) return $user->admin_add_by ?: $user->id;

    return $user->id;
}

function currentOwnerUserType()
{
    $user = auth()->user();

    if (!$user) return null;

    if ($user->u_type == 4) return 1;
    if ($user->u_type == 5) return 2;
    if ($user->u_type == 6) return 3;

    return $user->u_type;
}

function checkCoreAccess($featureName)
{
    $user = auth()->user();
    if (!$user) return null;

    $userType = $user->u_type;
    $features = view()->shared('userFeatures') ?? [];

    // ---------------- User Company----------------
    if ($userType == 2) {

        abort_if(
            !in_array('ALL', $features) && !in_array($featureName, $features),
            403
        );
    }

    // ---------------- EMPLOYEE ----------------
    if ($userType == 5) {
        $empPermissions = $user->emp_permission
            ? array_map('trim', explode(',', $user->emp_permission))
            : [];

        abort_if(
            (
                !in_array('ALL', $features) && !in_array($featureName, $features)
            ) ||
            (
                !in_array('ALL', $empPermissions) && !in_array($featureName, $empPermissions)
            ),
            403
        );
    }
}

//For CA-Accountant access
function getAccessCompanyId($request)
{
    $userId = null;
    if ($request->filled('compId')) {
        try {
            $compId = decrypt($request->compId);
            session(['compId' => $compId]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    if (session()->has('compId')) {
        $userId = session('compId');
    }
    return $userId;
}

function parentCompanyName()
{
	$user = auth()->user();
    if (!$user) return null;
	
	$userId = currentOwnerId();
    if ($user->u_type == 1 || $user->u_type == 4){
		$userId = session('compId');
	}
	
	return DB::table('company_profiles')
			->where('userId', $userId)
			->value('comp_name');
}
