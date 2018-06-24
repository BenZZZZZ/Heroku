<?php

use Symfony\Component\HttpFoundation\Request;

$silex->post('users', function(Request $request) use($silex, $users) {
    return $silex->json(rF($users, 'listAllUsers', [
        $request->query->all(), $request->request->all()
    ]));
});

$silex->get('users/{id}/roles', function($id) use($silex, $users) {
    return $silex->json(rF($users, 'userRoles', [$id], ['admin']));
});

$silex->get('roles/{id}/perms', function($id) use($silex, $users) {
    return $silex->json(rF($users, 'rolePerms', [$id], ['superadmin']));
});

$silex->get('users/{id}/perms', function($id) use($silex, $users) {
    return $silex->json(rF($users, 'userPerms', [$id], ['admin']));
});

$silex->post('users/roles/update', function(Request $request) use(
    $silex, $users
) {
    return $silex->json(
        rF($users, 'updateUserRoles', [$request->request->all()], ['admin'])
    );
});

$silex->post('roles/perms/update', function(Request $request) use(
    $silex, $users
) {
    return $silex->json(rF(
        $users, 'updateRolePerms',
        [$request->request->all()],
        ['superadmin']
    ));
});

$silex->post('users/perms/update', function(Request $request) use(
    $silex, $users
) {
    return $silex->json(
        rF($users, 'updateUserPerms', [$request->request->all()], ['admin'])
    );
});