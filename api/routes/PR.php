<?php

use Symfony\Component\HttpFoundation\Request;

$silex->post('pr/reviewers/list', function(Request $request) use($silex, $reviewer) {
    return $silex->json(rF($reviewer, 'reviewerList', [
        $request->query->all(), $request->request->all()
    ]));
});

$silex->post('/pr/reviewer/{id}/student/list', function(Request $request, $id) use($silex, $reviewer) {
    return $silex->json(rF($reviewer, 'reviewerStudentList', [
        $id, $request->query->all(), $request->request->all()
    ]));
});

$silex->post('/pr/reviewer/{id}/student/update', function(Request $request, $id) use($silex, $reviewer) {
    return $silex->json(rF($reviewer, 'reviewerStudentUpdate', [
        $id, $request->query->all(), $request->request->all()
    ]));
});

$silex->get('/pr/reviewer/{id}/student/list', function(Request $request, $id) use($silex, $reviewer) {
    return $silex->json(rF($reviewer, 'reviewerStudentList', [
        $id, [], []
    ]));
});

$silex->post('/pr/student/list', function(Request $request) use($silex, $student) {
    return $silex->json(rF($student, 'studentList', [
        $request->query->all(), $request->request->all()
    ]));
});

$silex->get('/pr/docEditor', function() use($silex, $docs) {
    return $silex->json(rF($docs, 'getDoc', []));
});

$silex->post('/pr/docEditor/update', function(Request $request) use($silex, $docs) {
    return $silex->json(rF($docs, 'updateDoc', [$request->request->all()]));
});