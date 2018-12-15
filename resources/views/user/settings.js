var app = angular.module('settings', []);

app.controller('setting-controller', function($scope, $http) {
    $scope.loading = false;
    $scope.answer = '';
    $scope.data = [];

    $scope.pushData = function () {
        if (! $scope.selected || $scope.answer.length < 1) return;

        var data = {
            id: $scope.selected.id,
            question: $scope.selected.description,
            answer: $scope.answer
        };

        var questionIndex = $scope.questions.findIndex(function (value) {
            return value.id === $scope.selected.id;
        });

        if (questionIndex === -1) return;

        $scope.questions.splice(questionIndex, 1);
        $scope.selected = $scope.questions[0];
        $scope.answer = '';
        $scope.data.push(data);
    };

    $scope.removeData = function (id) {
        var index = $scope.data.findIndex(function (value) {
            return value.id === id;
        });

        if (index === -1) return;

        $scope.questions.push({
            id: $scope.data[index].id,
            description: $scope.data[index].question
        });
        $scope.selected = $scope.questions[0];
        $scope.data.splice(index, 1);
    };

    var fetchQuestions = function () {
        $scope.loading = true;

        $http.get("{{ path_for('2fa.getQuestions') }}")
            .then(function(response) {
                var data = response.data;

                if (data.status === 'success' && $scope.twoFactor === 'on') {
                    $scope.data = data.selected;

                    if ($scope.data.length > 0) {
                        data.questions = data.questions.filter(function (value) {
                            var idx = $scope.data.findIndex(function (value2) {
                                return value2.id === value.id;
                            });

                            return idx === -1;
                        });
                    }

                    $scope.questions = data.questions;
                    $scope.selected = $scope.questions[0];

                    return;
                }

                if ($scope.twoFactor === 'on') {
                    throw data.error;
                }
            }).catch(function (err) {
                $scope.twoFactor = 'off';
            }).finally(function () {
                $scope.loading = false;
        });
    };

    $scope.init = function (twoFactor) {
        $scope.twoFactor = twoFactor;

        if ($scope.twoFactor === 'on') {
            fetchQuestions();
        }
    };

    $scope.stateChanged = function () {
        $scope.success = false;
        $scope.warning = false;

        if ($scope.twoFactor === 'on') {
            fetchQuestions();
            return;
        }

        $scope.data = [];
        $scope.loading = false;
        $scope.questions = [];
    };

    $scope.submit = function () {
        $scope.success = false;
        $scope.warning = false;
        $scope.submitting = true;

        var data = {
            data: {
                two_step: $scope.twoFactor,
                questions: $scope.data
            },
            "{{ csrf_name_key }}": "{{ csrf_token_name }}",
            "{{ csrf_value_key }}": "{{ csrf_token_value }}"
        };

        if ($scope.twoFactor === 'off') {
            $http.post("{{ path_for('user.settings') }}", data)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data.status !== 'success')
                        throw response.data;

                    $scope.success = true;
                }).catch(function (reason) {
                    $scope.warning = true;
                }).finally(function () {
                    $scope.submitting = false;
            });

            return;
        }

        if ($scope.data.length <= 0) return;

        $http.post("{{ path_for('user.settings') }}", data)
            .then(function (response) {
                console.log(response.data);
                if (response.data.status !== 'success')
                    throw response.data;

                $scope.success = true;
            }).catch(function (reason) {
                $scope.warning = true;
            }).finally(function () {
                $scope.submitting = false;
        });
    };
});