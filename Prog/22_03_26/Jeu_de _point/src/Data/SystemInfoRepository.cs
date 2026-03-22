using System;
using System.Threading;
using System.Threading.Tasks;
using Npgsql;

namespace Data;

public sealed class SystemInfoRepository : ISystemInfoRepository
{
    private readonly IDbConnectionFactory _connectionFactory;

    public SystemInfoRepository(IDbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<DateTime> GetServerTimeAsync(CancellationToken cancellationToken = default)
    {
        await using var connection = await _connectionFactory.CreateOpenConnectionAsync(cancellationToken);
        await using var command = connection.CreateCommand();
        command.CommandText = "SELECT NOW()";

        var result = await command.ExecuteScalarAsync(cancellationToken);
        return result is DateTime time ? time : DateTime.MinValue;
    }
}
