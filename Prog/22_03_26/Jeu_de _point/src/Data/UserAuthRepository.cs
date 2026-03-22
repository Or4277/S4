using System.Threading;
using System.Threading.Tasks;
using Npgsql;

namespace Data;

public sealed class UserAuthRepository : IUserAuthRepository
{
    private readonly IDbConnectionFactory _connectionFactory;

    public UserAuthRepository(IDbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<bool> ValidateCredentialsAsync(
        string nom,
        string motDePasse,
        CancellationToken cancellationToken = default)
    {
        await using var connection = await _connectionFactory.CreateOpenConnectionAsync(cancellationToken);
        await using var command = new NpgsqlCommand(
            "SELECT 1 FROM utilisateur WHERE nom = @nom AND mot_de_passe = @mdp LIMIT 1;",
            (NpgsqlConnection)connection);
        command.Parameters.Add(new NpgsqlParameter("nom", nom));
        command.Parameters.Add(new NpgsqlParameter("mdp", motDePasse));

        var result = await command.ExecuteScalarAsync(cancellationToken);
        return result != null;
    }
}
