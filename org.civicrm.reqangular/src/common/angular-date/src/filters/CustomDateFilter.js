module.exports = function ($filter, DateFactory) {
    return function (datetime) {
        var Date;

        if(typeof datetime == 'object'){
            datetime = $filter('date')(datetime, 'dd/MM/yyyy');
        }

        Date = DateFactory.createDate(datetime, [
            'DD-MM-YYYY',
            'DD-MM-YYYY HH:mm:ss',
            'YYYY-MM-DD',
            'YYYY-MM-DD HH:mm:ss',
            'DD/MM/YYYY',
            'x'
        ], true);

        var beginningOfEra = DateFactory.createDate(0);
        var notEmpty = !Date.isSame(beginningOfEra);

        if(Date.isValid() && notEmpty) return Date.format('DD/MM/YYYY');

        return 'Unspecified';
    };
};